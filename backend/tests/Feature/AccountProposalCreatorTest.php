<?php

use App\Filament\Account\Resources\ProposalResource\Pages\ListProposals;
use App\Filament\Account\Resources\UserResource\Pages\ManageUsers;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\Role;
use App\Models\User;
use App\Support\ProposalReport;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
    $this->travelTo(now()->setDate(2026, 10, 15));
});

/** A proposal created while $user is logged in, optionally finalised with a payment. */
function proposalBy(User $user, float $quoted, ?float $final = null, float $paid = 0): Proposal
{
    test()->actingAs($user);

    $proposal = Proposal::create([
        'proposal_company_id' => ProposalCompany::where('code', 'RT')->value('id'),
        'proposal_date' => now(),
        'client_name' => 'Client of '.$user->name,
        'subject' => 'Thesis support',
        'items' => [['work' => 'Full package', 'amount' => $quoted]],
    ]);

    if ($final !== null) {
        $proposal->update(['status' => Proposal::STATUS_FINAL, 'final_amount' => $final, 'advance_amount' => 0, 'finalized_at' => now()]);
        $paid && $proposal->payments()->create(['amount' => $paid, 'paid_on' => now()]);
    }

    return $proposal;
}

function salesPerson(string $name): User
{
    $role = Role::firstOrCreate(['name' => 'Sales'], ['permissions' => ['proposals' => ['view', 'create', 'edit']]]);

    return User::factory()->create(['name' => $name, 'role_id' => $role->id]);
}

it('saves who created the proposal and shows it in the list', function () {
    $priya = salesPerson('Priya Sharma');
    $proposal = proposalBy($priya, 20000);

    expect($proposal->created_by)->toBe($priya->id);

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListProposals::class)
        ->assertSee('Priya Sharma')
        ->assertSee('Sales');
});

it('filters the proposal list by the person who created it', function () {
    $priya = salesPerson('Priya Sharma');
    $amit = salesPerson('Amit Jain');
    $byPriya = proposalBy($priya, 20000);
    $byAmit = proposalBy($amit, 30000);

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListProposals::class)
        ->filterTable('created_by', [$priya->id])
        ->assertCanSeeTableRecords([$byPriya])
        ->assertCanNotSeeTableRecords([$byAmit]);
});

it('totals the work done by each person in the report', function () {
    $priya = salesPerson('Priya Sharma');
    $amit = salesPerson('Amit Jain');
    proposalBy($priya, 20000, final: 18000, paid: 5000);
    proposalBy($priya, 10000);
    proposalBy($amit, 40000);

    $this->actingAs(User::factory()->admin()->create());
    $people = ProposalReport::fromArray(['period' => 'this_month'])->byCreator()->keyBy('name');

    expect($people['Priya Sharma'])->toMatchArray([
        'proposals' => 2, 'final' => 1, 'conversion' => 50, 'quoted' => 30000.0, 'final_value' => 18000.0, 'received' => 5000.0,
    ])
        ->and($people['Amit Jain'])->toMatchArray(['proposals' => 1, 'final' => 0, 'quoted' => 40000.0]);

    $onlyPriya = ProposalReport::fromArray(['period' => 'this_month', 'creators' => [$priya->id]]);

    expect($onlyPriya->proposals)->toHaveCount(2)
        ->and($onlyPriya->receipts->sum('amount'))->toBe(5000.0)
        ->and($onlyPriya->title())->toContain('Priya Sharma')
        ->and($onlyPriya->filename('pdf'))->toBe('proposal-report-priya-sharma-2026-10-01-to-2026-10-31.pdf');
});

it('downloads one person\'s report as PDF and Excel', function () {
    $priya = salesPerson('Priya Sharma');
    proposalBy($priya, 20000, final: 18000, paid: 5000);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('proposals.report', ['period' => 'this_month', 'creators' => [$priya->id], 'format' => 'pdf']))
        ->assertOk()->assertDownload('proposal-report-priya-sharma-2026-10-01-to-2026-10-31.pdf');

    $this->actingAs($admin)->get(route('proposals.report', ['period' => 'this_month', 'format' => 'excel']))
        ->assertOk()->assertDownload('proposal-report-2026-10-01-to-2026-10-31.xlsx');
});

it('starts a person\'s work report from the proposals and users lists', function () {
    $priya = salesPerson('Priya Sharma');
    proposalBy($priya, 20000);
    $nobody = salesPerson('No Proposals');

    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(ListProposals::class)
        ->callAction('report', ['period' => 'this_month', 'creators' => [$priya->id], 'format' => 'excel'])
        ->assertHasNoActionErrors();

    Livewire::test(ManageUsers::class)
        ->assertTableActionVisible('workReport', $priya)
        ->assertTableActionHidden('workReport', $nobody)
        ->callTableAction('workReport', $priya, ['period' => 'this_month', 'format' => 'pdf'])
        ->assertHasNoTableActionErrors();
});
