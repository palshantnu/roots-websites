<?php

use App\Filament\Account\Resources\ProposalResource\Pages\CreateProposal;
use App\Filament\Account\Resources\ProposalResource\Pages\EditProposal;
use App\Filament\Account\Resources\ProposalResource\Pages\ListProposals;
use App\Filament\Account\Resources\ProposalResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Account\Resources\RoleResource\Pages\CreateRole;
use App\Models\AccountService;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\Role;
use App\Models\User;
use App\Support\AccountPermissions;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
});

function makeProposal(array $overrides = []): Proposal
{
    return Proposal::create([
        'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
        'proposal_date' => now(),
        'client_name' => 'Asha Verma',
        'subject' => 'Thesis writing support',
        'items' => [
            ['work' => 'Token Amount', 'amount' => 1500],
            ['work' => 'Chapter 1', 'amount' => 5700],
            ['work' => 'Chapter 2', 'amount' => 5700],
        ],
        ...$overrides,
    ]);
}

it('seeds the three proposal companies', function () {
    expect(ProposalCompany::orderBy('sort_order')->pluck('name')->all())
        ->toBe(['Roots Technology', 'RTS Publication', 'ThesisCraft Academy']);
});

it('numbers proposals per company and works out the total', function () {
    $first = makeProposal();
    $second = makeProposal();

    expect($first->proposal_no)->toBe('RT/'.now()->year.'/0001')
        ->and($second->proposal_no)->toBe('RT/'.now()->year.'/0002')
        ->and((float) $first->total_amount)->toBe(12900.0);
});

it('loads every accounts page for a super admin', function () {
    $admin = User::factory()->admin()->create();
    $proposal = makeProposal();
    $role = Role::create(['name' => 'Accountant', 'permissions' => AccountPermissions::all()]);

    $pages = [
        '/accounts/proposals', '/accounts/proposals/create', "/accounts/proposals/{$proposal->id}/edit",
        '/accounts/proposal-companies',
        '/accounts/roles', '/accounts/roles/create', "/accounts/roles/{$role->id}/edit",
        '/accounts/users',
    ];

    foreach ($pages as $page) {
        $this->actingAs($admin)->get($page)->assertOk();
    }
});

it('creates a proposal and sends the user to the list to download its PDF', function () {
    $this->actingAs(User::factory()->admin()->create());
    $service = AccountService::firstOrCreate(['name' => 'Research']);

    Livewire::test(CreateProposal::class)
        ->fillForm([
            'proposal_company_id' => ProposalCompany::where('code', 'TCA')->value('id'),
            'account_service_id' => $service->id,
            'proposal_date' => now()->toDateString(),
            'client_name' => 'Ravi Kumar',
            'subject' => 'PhD synopsis',
            'items' => [['work' => 'Token Amount', 'amount' => 1500], ['work' => 'Synopsis', 'amount' => 6500]],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirectContains('download=');

    $proposal = Proposal::sole();
    expect($proposal->proposal_no)->toStartWith('TCA/')
        ->and((float) $proposal->total_amount)->toBe(8000.0)
        ->and($proposal->account_service_id)->toBe($service->id);
});

it('downloads the proposal as a PDF', function () {
    $proposal = makeProposal();

    $this->actingAs(User::factory()->admin()->create())
        ->get(route('proposals.pdf', $proposal))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('RT-'.now()->year.'-0001.pdf');
});

it('finalises a proposal with the final and advance amount', function () {
    $this->actingAs(User::factory()->admin()->create());
    $proposal = makeProposal();

    Livewire::test(ListProposals::class)
        ->callTableAction('finalize', $proposal, ['final_amount' => 12000, 'advance_amount' => 1500])
        ->assertHasNoTableActionErrors()
        ->assertSet('activeTab', 'final');

    $proposal->refresh();
    expect($proposal->isFinal())->toBeTrue()
        ->and((float) $proposal->final_amount)->toBe(12000.0)
        ->and((float) $proposal->advance_amount)->toBe(1500.0)
        ->and($proposal->balance)->toBe(10500.0);
});

function makeFinalProject(): Proposal
{
    $proposal = makeProposal(); // total 12,900
    $proposal->update([
        'status' => Proposal::STATUS_FINAL,
        'final_amount' => 12000,
        'advance_amount' => 1500,
        'finalized_at' => now(),
    ]);

    return $proposal;
}

it('records payments on a final project and shows what remains', function () {
    $this->actingAs(User::factory()->admin()->create());
    $proposal = makeFinalProject();

    Livewire::test(ListProposals::class)
        ->set('activeTab', 'final')
        ->callTableAction('addPayment', $proposal, [
            'amount' => 5700,
            'paid_on' => now()->toDateString(),
            'stage' => 'Chapter 1',
            'mode' => 'UPI',
        ])
        ->assertHasNoTableActionErrors();

    $proposal->refresh();
    expect($proposal->payments)->toHaveCount(1)
        ->and($proposal->received)->toBe(7200.0)
        ->and($proposal->balance)->toBe(4800.0);
});

it('does not accept a payment larger than what remains', function () {
    $this->actingAs(User::factory()->admin()->create());
    $proposal = makeFinalProject(); // remaining 10,500

    Livewire::test(ListProposals::class)
        ->set('activeTab', 'final')
        ->callTableAction('addPayment', $proposal, ['amount' => 11000, 'paid_on' => now()->toDateString()])
        ->assertHasTableActionErrors(['amount']);

    expect($proposal->payments()->count())->toBe(0);
});

it('lists the payment history on the final project page', function () {
    $this->actingAs(User::factory()->admin()->create());
    $proposal = makeFinalProject();
    $proposal->payments()->create(['amount' => 5700, 'paid_on' => now(), 'stage' => 'Chapter 1']);

    Livewire::test(PaymentsRelationManager::class, ['ownerRecord' => $proposal, 'pageClass' => EditProposal::class])
        ->assertOk()
        ->assertCanSeeTableRecords($proposal->payments)
        ->assertSee('Remaining');

    $this->get("/accounts/proposals/{$proposal->id}/edit")
        ->assertOk()
        ->assertSee('Total received');

    // Not shown for a proposal that is not final yet.
    expect(PaymentsRelationManager::canViewForRecord(makeProposal(), EditProposal::class))->toBeFalse();
});

it('saves full access from the role form', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateRole::class)
        ->fillForm(['name' => 'Manager'])
        ->set('data.full_access', true)
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Role::sole()->permissionCount())
        ->toBe(count(AccountPermissions::MODULES) * count(AccountPermissions::ACTIONS));
});

it('limits a staff user to what their role allows', function () {
    $role = Role::create(['name' => 'Sales', 'permissions' => ['proposals' => ['view', 'create']]]);
    $staff = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($staff);
    $proposal = makeProposal();

    $this->get('/accounts/proposals')->assertOk();
    $this->get('/accounts/proposals/create')->assertOk();
    $this->get("/accounts/proposals/{$proposal->id}/edit")->assertForbidden();
    $this->get('/accounts/expenses')->assertForbidden();
    $this->get('/accounts/roles')->assertForbidden();
    $this->get(route('proposals.pdf', $proposal))->assertOk();

    // Main website admin stays closed to staff.
    $this->get('/admin')->assertForbidden();
});

it('formats amounts with Indian digit grouping', function () {
    expect(Proposal::formatAmount(1500))->toBe('1,500')
        ->and(Proposal::formatAmount(125000))->toBe('1,25,000')
        ->and(Proposal::formatAmount(5700.5))->toBe('5,700.50')
        ->and(Proposal::formatAmount(999))->toBe('999');
});

it('keeps users without a role out of the accounts panel', function () {
    $this->actingAs(User::factory()->create())
        ->get('/accounts')
        ->assertForbidden();
});
