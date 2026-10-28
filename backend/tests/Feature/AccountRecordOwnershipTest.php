<?php

use App\Filament\Account\Resources\B2bProjectResource\Pages\ListB2bProjects;
use App\Filament\Account\Resources\ExpenseResource\Pages\ListExpenses;
use App\Filament\Account\Resources\MarketingExpenseResource\Pages\ListMarketingExpenses;
use App\Filament\Account\Resources\ProposalResource;
use App\Filament\Account\Resources\ProposalResource\Pages\ListProposals;
use App\Filament\Account\Resources\RoleResource\Pages\CreateRole;
use App\Filament\Account\Resources\WorkStreamResource\Pages\ListWorkStreams;
use App\Models\AccountService;
use App\Models\B2bClient;
use App\Models\B2bPayment;
use App\Models\B2bProject;
use App\Models\Expense;
use App\Models\MarketingChannel;
use App\Models\MarketingExpense;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\ProposalPayment;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkStream;
use App\Support\AccountMetrics;
use App\Support\AccountPermissions;
use App\Support\ProposalReport;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
    $this->travelTo(now()->setDate(2026, 10, 15));
});

/** A user whose role has full access to every module; managers also see everyone's records. */
function staff(string $name, bool $manager = false): User
{
    $role = Role::firstOrCreate(
        ['name' => $manager ? 'Manager' : 'Staff'],
        ['permissions' => AccountPermissions::all(), 'sees_all_records' => $manager],
    );

    return User::factory()->create(['name' => $name, 'role_id' => $role->id]);
}

/** Creates a final proposal with a payment, as $user. */
function ownProposal(User $user, float $amount = 10000): Proposal
{
    test()->actingAs($user);

    $proposal = Proposal::create([
        'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
        'proposal_date' => now(),
        'client_name' => 'Client of '.$user->name,
        'subject' => 'Thesis support',
        'items' => [['work' => 'Package', 'amount' => $amount]],
    ]);
    $proposal->update(['status' => Proposal::STATUS_FINAL, 'final_amount' => $amount, 'advance_amount' => 0, 'finalized_at' => now()]);
    $proposal->payments()->create(['amount' => $amount / 2, 'paid_on' => now()]);

    return $proposal;
}

it('shows a staff user only the proposals they created', function () {
    $priya = staff('Priya');
    $amit = staff('Amit');
    $mine = ownProposal($priya);
    $theirs = ownProposal($amit);

    $this->actingAs($priya);

    expect(Proposal::pluck('id')->all())->toBe([$mine->id])
        ->and(ProposalPayment::count())->toBe(1);

    Livewire::test(ListProposals::class)
        ->set('activeTab', 'final')
        ->assertCanSeeTableRecords([$mine])
        ->assertCanNotSeeTableRecords([$theirs]);
});

it('lets a staff user open, download and change only their own proposals', function () {
    $priya = staff('Priya');
    $amit = staff('Amit');
    $mine = ownProposal($priya);
    $theirs = ownProposal($amit);

    $this->actingAs($priya);

    $this->get("/accounts/proposals/{$mine->id}/edit")->assertOk();
    $this->get(route('proposals.pdf', $mine))->assertOk();
    $this->get(route('proposals.invoice', [$mine, 'payment' => 'all']))->assertOk();

    $this->get("/accounts/proposals/{$theirs->id}/edit")->assertNotFound();
    $this->get(route('proposals.pdf', $theirs))->assertNotFound();
    $this->get(route('proposals.invoice', [$theirs, 'payment' => 'all']))->assertNotFound();
});

it('keeps proposal and project numbers running across everyone', function () {
    ownProposal(staff('Priya'));
    $second = ownProposal(staff('Amit'));

    expect($second->proposal_no)->toBe('RT/2026/0002');

    $this->actingAs($priya = staff('Priya 2'));
    $client = B2bClient::create(['company_name' => 'Acme']);
    B2bProject::create(['b2b_client_id' => $client->id, 'title' => 'One', 'value' => 100]);

    $this->actingAs(staff('Amit 2'));
    $client = B2bClient::create(['company_name' => 'Nova']);
    $project = B2bProject::create(['b2b_client_id' => $client->id, 'title' => 'Two', 'value' => 100]);

    expect($project->project_no)->toBe('B2B/2026/0002');
});

it('limits reports and dashboard figures to the staff user\'s own work', function () {
    $priya = staff('Priya');
    ownProposal($priya, 10000);
    ownProposal(staff('Amit'), 40000);

    $this->actingAs($priya);

    $report = ProposalReport::fromArray(['period' => 'this_month']);
    expect($report->proposals)->toHaveCount(1)
        ->and($report->receipts->sum('amount'))->toBe(5000.0)
        ->and($report->byCreator()->pluck('name')->all())->toBe(['Priya'])
        ->and(ProposalResource::creatorOptions())->toBe([$priya->id => 'Priya'])
        ->and(AccountMetrics::receivedByMonth(1)['2026-10'])->toBe(5000.0)
        ->and(AccountMetrics::pendingDues())->toBe(['amount' => 5000.0, 'count' => 1]);
});

it('lets admins and manager roles see everyone\'s records', function () {
    ownProposal(staff('Priya'), 10000);
    ownProposal(staff('Amit'), 40000);

    $this->actingAs(User::factory()->admin()->create());
    expect(Proposal::count())->toBe(2)
        ->and(AccountMetrics::receivedByMonth(1)['2026-10'])->toBe(25000.0);

    $this->actingAs(staff('Neha', manager: true));
    expect(Proposal::count())->toBe(2);
    Livewire::test(ListProposals::class)->set('activeTab', 'final')->assertSee('Priya')->assertSee('Amit');
});

it('applies the same rule to B2B, marketing, expenses and work streams', function () {
    $priya = staff('Priya');
    $amit = staff('Amit');
    $service = AccountService::firstOrCreate(['name' => 'IT']);
    $channel = MarketingChannel::where('name', 'Google Ads')->firstOrFail();

    $make = function (User $user) use ($service, $channel) {
        test()->actingAs($user);
        $client = B2bClient::create(['company_name' => "{$user->name} Client"]);
        $project = B2bProject::create(['b2b_client_id' => $client->id, 'title' => "{$user->name} project", 'value' => 1000]);
        $project->payments()->create(['amount' => 100, 'paid_on' => now()]);

        return [
            'project' => $project,
            'marketing' => MarketingExpense::create(['marketing_channel_id' => $channel->id, 'title' => "{$user->name} ads", 'amount' => 500, 'spent_on' => now()]),
            'expense' => Expense::create(['title' => "{$user->name} rent", 'amount' => 200, 'expense_date' => now()]),
            'work' => WorkStream::create(['title' => "{$user->name} work", 'account_service_id' => $service->id, 'cost' => 300]),
        ];
    };

    $mine = $make($priya);
    $theirs = $make($amit);

    $this->actingAs($priya);

    expect($mine['expense']->created_by)->toBe($priya->id)
        ->and($mine['work']->created_by)->toBe($priya->id)
        ->and($mine['project']->fresh()->client->created_by)->toBe($priya->id);

    expect(B2bClient::pluck('company_name')->all())->toBe(['Priya Client'])
        ->and(B2bPayment::count())->toBe(1)
        ->and(AccountMetrics::expensesByMonth(1)['2026-10'])->toBe(700.0);

    Livewire::test(ListB2bProjects::class)->assertCanSeeTableRecords([$mine['project']])->assertCanNotSeeTableRecords([$theirs['project']]);
    Livewire::test(ListMarketingExpenses::class)->assertCanSeeTableRecords([$mine['marketing']])->assertCanNotSeeTableRecords([$theirs['marketing']]);
    Livewire::test(ListExpenses::class)->assertCanSeeTableRecords([$mine['expense']])->assertCanNotSeeTableRecords([$theirs['expense']]);
    Livewire::test(ListWorkStreams::class)->assertCanSeeTableRecords([$mine['work']])->assertCanNotSeeTableRecords([$theirs['work']]);

    $this->get("/accounts/b2b-projects/{$theirs['project']->id}/edit")->assertNotFound();
    $this->get(route('b2b.invoice', [$theirs['project'], 'payment' => 'all']))->assertNotFound();
});

it('saves the "see everyone\'s records" switch on the role form', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateRole::class)
        ->fillForm(['name' => 'Team lead', 'sees_all_records' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Role::sole()->sees_all_records)->toBeTrue();
});
