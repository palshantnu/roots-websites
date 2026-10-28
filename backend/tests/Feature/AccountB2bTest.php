<?php

use App\Filament\Account\Resources\B2bClientResource\Pages\CreateB2bClient;
use App\Filament\Account\Resources\B2bClientResource\Pages\EditB2bClient;
use App\Filament\Account\Resources\B2bClientResource\Pages\ListB2bClients;
use App\Filament\Account\Resources\B2bClientResource\RelationManagers\ProjectsRelationManager;
use App\Filament\Account\Resources\B2bProjectResource\Pages\CreateB2bProject;
use App\Filament\Account\Resources\B2bProjectResource\Pages\EditB2bProject;
use App\Filament\Account\Resources\B2bProjectResource\Pages\ListB2bProjects;
use App\Filament\Account\Resources\B2bProjectResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Account\Widgets\AccountStatsOverview;
use App\Filament\Account\Widgets\B2bClientsChart;
use App\Filament\Account\Widgets\B2bDuesTable;
use App\Models\AccountService;
use App\Models\B2bClient;
use App\Models\B2bProject;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\Role;
use App\Models\User;
use App\Support\AccountMetrics;
use App\Support\B2bReport;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Number;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
    $this->travelTo(now()->setDate(2026, 10, 15));
});

function makeB2bClient(string $name = 'Acme Institute'): B2bClient
{
    return B2bClient::create([
        'company_name' => $name,
        'contact_person' => 'Meera Rao',
        'gstin' => '29ABCDE1234F1Z5',
        'city' => 'Bengaluru',
    ]);
}

function makeB2bProject(B2bClient $client, array $overrides = []): B2bProject
{
    return B2bProject::create($overrides + [
        'b2b_client_id' => $client->id,
        'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
        'account_service_id' => AccountService::firstOrCreate(['name' => 'IT'])->id,
        'title' => 'LMS development',
        'value' => 100000,
        'start_date' => now(),
    ]);
}

it('opens every B2B page for an admin', function () {
    $project = makeB2bProject($client = makeB2bClient());

    $pages = [
        '/accounts/b2b-clients', '/accounts/b2b-clients/create', "/accounts/b2b-clients/{$client->id}/edit",
        '/accounts/b2b-projects', '/accounts/b2b-projects/create', "/accounts/b2b-projects/{$project->id}/edit",
    ];

    foreach ($pages as $page) {
        $this->actingAs(User::factory()->admin()->create())->get($page)->assertOk();
    }
});

it('creates a client and a numbered project for it', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateB2bClient::class)
        ->fillForm(['company_name' => 'Acme Institute', 'contact_person' => 'Meera Rao', 'city' => 'Pune'])
        ->call('create')
        ->assertHasNoFormErrors();

    $client = B2bClient::sole();

    Livewire::test(CreateB2bProject::class)
        ->fillForm([
            'b2b_client_id' => $client->id,
            'title' => 'LMS development',
            'account_service_id' => AccountService::firstOrCreate(['name' => 'IT'])->id,
            'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
            'value' => 150000,
            'status' => 'in_progress',
            'start_date' => now()->toDateString(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Added from the client's Projects tab, already completed.
    Livewire::test(ProjectsRelationManager::class, ['ownerRecord' => $client, 'pageClass' => EditB2bClient::class])
        ->callTableAction('create', data: [
            'title' => 'Website revamp',
            'account_service_id' => AccountService::firstOrCreate(['name' => 'IT'])->id,
            'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
            'value' => 40000,
            'status' => 'completed',
            'start_date' => now()->toDateString(),
        ])
        ->assertHasNoTableActionErrors();

    expect(B2bProject::orderBy('id')->pluck('project_no')->all())->toBe(['B2B/2026/0001', 'B2B/2026/0002'])
        ->and(B2bProject::latest('id')->first()->completed_on?->toDateString())->toBe('2026-10-15')
        ->and($client->fresh()->business)->toBe(190000.0);
});

it('records payments up to the remaining amount', function () {
    $this->actingAs(User::factory()->admin()->create());
    $project = makeB2bProject(makeB2bClient());

    Livewire::test(ListB2bProjects::class)
        ->callTableAction('addPayment', $project, ['amount' => 30000, 'paid_on' => now()->toDateString(), 'mode' => 'UPI', 'reference' => 'UTR1'])
        ->assertHasNoTableActionErrors()
        ->callTableAction('addPayment', $project, ['amount' => 80000, 'paid_on' => now()->toDateString()])
        ->assertHasTableActionErrors(['amount']);

    Livewire::test(PaymentsRelationManager::class, ['ownerRecord' => $project, 'pageClass' => EditB2bProject::class])
        ->callTableAction('create', data: ['amount' => 20000, 'paid_on' => now()->toDateString()])
        ->assertHasNoTableActionErrors();

    $project->refresh();
    expect($project->received)->toBe(50000.0)
        ->and($project->balance)->toBe(50000.0)
        ->and($project->client->received)->toBe(50000.0);
});

it('filters projects by client, dues and status tab', function () {
    $this->actingAs(User::factory()->admin()->create());
    $acme = makeB2bClient();
    $due = makeB2bProject($acme);
    $paid = makeB2bProject($acme, ['title' => 'Paid', 'value' => 1000, 'status' => 'completed']);
    $paid->payments()->create(['amount' => 1000, 'paid_on' => now()]);
    $other = makeB2bProject(makeB2bClient('Other Ltd'));

    Livewire::test(ListB2bProjects::class)
        ->filterTable('b2b_client_id', $acme->id)
        ->assertCanSeeTableRecords([$due, $paid])
        ->assertCanNotSeeTableRecords([$other])
        ->filterTable('with_dues', true)
        ->assertCanSeeTableRecords([$due])
        ->assertCanNotSeeTableRecords([$paid])
        ->resetTableFilters()
        ->set('activeTab', 'completed')
        ->assertCanSeeTableRecords([$paid])
        ->assertCanNotSeeTableRecords([$due, $other]);
});

it('shows each client with its total business and outstanding', function () {
    $this->actingAs(User::factory()->admin()->create());
    $client = makeB2bClient();
    makeB2bProject($client, ['value' => 125000])->payments()->create(['amount' => 25000, 'paid_on' => now()]);

    Livewire::test(ListB2bClients::class)
        ->assertSee('Acme Institute')
        ->assertSee(Number::currency(125000, 'INR'))
        ->assertSee(Number::currency(100000, 'INR'));
});

it('downloads one payment invoice or one combined invoice', function () {
    $project = makeB2bProject(makeB2bClient());
    $first = $project->payments()->create(['amount' => 10000, 'paid_on' => now()->subDays(5)]);
    $second = $project->payments()->create(['amount' => 15000, 'paid_on' => now()]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('b2b.invoice', [$project, 'payment' => 'all']))
        ->assertOk()->assertDownload('INV-B2B-2026-0001.pdf');

    $this->actingAs($admin)->get(route('b2b.invoice', [$project, 'payment' => $second->id]))
        ->assertOk()->assertDownload('INV-B2B-2026-0001-2.pdf');

    $this->actingAs($admin)->get(route('b2b.invoice', [$project, 'payment' => 999]))->assertNotFound();

    expect(B2bProject::find($project->id)->receiptLines()->pluck('key')->all())->toBe([$first->id, $second->id]);
});

it('builds the business report for a period with client and service totals', function () {
    $acme = makeB2bClient();
    $old = makeB2bProject($acme, ['title' => 'Old', 'start_date' => '2026-08-01', 'value' => 50000]);
    $old->payments()->create(['amount' => 20000, 'paid_on' => '2026-10-03']);
    makeB2bProject($acme, ['value' => 80000]);
    makeB2bProject(makeB2bClient('Other Ltd'), ['value' => 30000, 'status' => 'cancelled']);

    $report = B2bReport::fromArray(['period' => 'this_month']);

    expect($report->projects)->toHaveCount(2)
        ->and($report->totals()['Business value'])->toBe(80000.0)
        ->and($report->totals()['Received in period'])->toBe(20000.0)
        ->and($report->byClient()->firstWhere('name', 'Acme Institute'))->toMatchArray([
            'projects' => 1, 'value' => 80000.0, 'received_in_period' => 20000.0,
        ]);

    $statement = B2bReport::fromArray(['client' => $acme->id]);
    expect($statement->period)->toBe('all_time')
        ->and($statement->projects)->toHaveCount(2)
        ->and($statement->totals()['Outstanding (these projects)'])->toBe(110000.0);
});

it('downloads the B2B report and statement as PDF and Excel', function () {
    $client = makeB2bClient();
    makeB2bProject($client)->payments()->create(['amount' => 5000, 'paid_on' => now()]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('b2b.report', ['period' => 'this_month', 'format' => 'pdf']))
        ->assertOk()->assertDownload('b2b-report-2026-10-01-to-2026-10-31.pdf');

    $this->actingAs($admin)->get(route('b2b.report', ['period' => 'this_month', 'format' => 'excel']))
        ->assertOk()->assertDownload('b2b-report-2026-10-01-to-2026-10-31.xlsx');

    $this->actingAs($admin)->get(route('b2b.report', ['client' => $client->id, 'format' => 'pdf']))
        ->assertOk()->assertDownload('statement-acme-institute-2000-01-01-to-2026-10-15.pdf');
});

it('starts downloads from the report, statement and invoice modals', function () {
    $this->actingAs(User::factory()->admin()->create());
    $client = makeB2bClient();
    $project = makeB2bProject($client);
    $project->payments()->create(['amount' => 5000, 'paid_on' => now()]);

    Livewire::test(ListB2bProjects::class)
        ->callAction('report', ['period' => 'custom', 'from' => '2026-10-01', 'to' => '2026-10-31', 'format' => 'excel'])
        ->assertHasNoActionErrors()
        ->callTableAction('invoice', $project, ['payment' => 'all'])
        ->assertHasNoTableActionErrors();

    Livewire::test(ListB2bClients::class)
        ->callTableAction('statement', $client, ['period' => 'all_time', 'format' => 'pdf'])
        ->assertHasNoTableActionErrors();

    Livewire::test(EditB2bProject::class, ['record' => $project->getRouteKey()])
        ->callAction('invoice', ['payment' => 'all'])
        ->assertHasNoActionErrors();
});

it('keeps B2B pages and downloads away from roles without B2B access', function () {
    $project = makeB2bProject(makeB2bClient());
    $project->payments()->create(['amount' => 5000, 'paid_on' => now()]);
    $role = Role::create(['name' => 'Proposals only', 'permissions' => ['proposals' => ['view']]]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user)->get('/accounts/b2b-clients')->assertForbidden();
    $this->actingAs($user)->get('/accounts/b2b-projects')->assertForbidden();
    $this->actingAs($user)->get(route('b2b.report'))->assertForbidden();
    $this->actingAs($user)->get(route('b2b.invoice', $project))->assertForbidden();
});

it('adds B2B money to the dashboard figures', function () {
    $this->actingAs(User::factory()->admin()->create());
    $client = makeB2bClient();
    makeB2bProject($client, ['value' => 60000])->payments()->create(['amount' => 10000, 'paid_on' => now()]);
    makeB2bProject($client, ['value' => 9000, 'status' => 'cancelled']);

    expect(AccountMetrics::receivedByMonth(1)['2026-10'])->toBe(10000.0)
        ->and(AccountMetrics::pendingDues())->toBe(['amount' => 50000.0, 'count' => 1]);

    Livewire::test(AccountStatsOverview::class)->assertSee('B2B business');
    Livewire::test(B2bClientsChart::class)->assertOk();
    Livewire::test(B2bDuesTable::class)->assertSee('B2B/2026/0001');
});

it('downloads the proposal report and payment invoices', function () {
    $proposal = Proposal::create([
        'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
        'account_service_id' => AccountService::firstOrCreate(['name' => 'Research'])->id,
        'proposal_date' => now(),
        'client_name' => 'Asha Verma',
        'subject' => 'Thesis writing support',
        'items' => [['work' => 'Token Amount', 'amount' => 10000]],
    ]);
    $proposal->update(['status' => Proposal::STATUS_FINAL, 'final_amount' => 10000, 'advance_amount' => 2000, 'finalized_at' => now()]);
    $payment = $proposal->payments()->create(['amount' => 3000, 'paid_on' => now()]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('proposals.invoice', [$proposal, 'payment' => 'advance']))
        ->assertOk()->assertDownload('INV-RT-2026-0001-1.pdf');
    $this->actingAs($admin)->get(route('proposals.invoice', [$proposal, 'payment' => $payment->id]))
        ->assertOk()->assertDownload('INV-RT-2026-0001-2.pdf');
    $this->actingAs($admin)->get(route('proposals.report', ['period' => 'this_week', 'format' => 'excel']))
        ->assertOk()->assertDownload('proposal-report-2026-10-12-to-2026-10-18.xlsx');
});
