<?php

use App\Filament\Account\Widgets\AccountStatsOverview;
use App\Filament\Account\Widgets\EmployeeWorkChart;
use App\Filament\Account\Widgets\ExpenseCategoryChart;
use App\Filament\Account\Widgets\IncomeExpenseChart;
use App\Filament\Account\Widgets\PendingCollectionsTable;
use App\Filament\Account\Widgets\ProfitChart;
use App\Filament\Account\Widgets\ProposalsChart;
use App\Filament\Account\Widgets\ServiceRevenueChart;
use App\Models\AccountService;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkStream;
use App\Support\AccountMetrics;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
    $this->travelTo(now()->setDate(2026, 10, 15));
});

function seedFinances(): Proposal
{
    $service = AccountService::firstOrCreate(['name' => 'IT']);
    $employee = Employee::create(['name' => 'Amit', 'monthly_salary' => 20000]);

    // This month: work stream advance 10,000 + paid instalment 5,000.
    $work = WorkStream::create(['title' => 'Website', 'account_service_id' => $service->id, 'employee_id' => $employee->id,
        'status' => 'completed', 'cost' => 50000, 'advance_payment' => 10000]);
    $work->payments()->create(['amount' => 5000, 'due_date' => now()->toDateString(), 'is_paid' => true]);
    $work->payments()->create(['amount' => 9999, 'due_date' => now()->toDateString(), 'is_paid' => false]); // not received

    // This month: final project advance 2,000 + payment 3,000. Last month: payment 1,000.
    $proposal = Proposal::create(['proposal_company_id' => ProposalCompany::first()->id, 'proposal_date' => now(),
        'client_name' => 'Ravi', 'subject' => 'Thesis', 'items' => [['work' => 'All', 'amount' => 20000]]]);
    $proposal->update(['status' => Proposal::STATUS_FINAL, 'final_amount' => 20000, 'advance_amount' => 2000, 'finalized_at' => now()]);
    $proposal->payments()->create(['amount' => 3000, 'paid_on' => now()]);
    $proposal->payments()->create(['amount' => 1000, 'paid_on' => now()->subMonth()]);

    Expense::create(['title' => 'Rent', 'category' => 'Office & Rent', 'amount' => 8000, 'expense_date' => now()]);
    Expense::create(['title' => 'Old', 'category' => 'Travel', 'amount' => 500, 'expense_date' => now()->subMonths(13)]);

    return $proposal;
}

it('adds up money received and spent per month', function () {
    seedFinances();

    $received = AccountMetrics::receivedByMonth(2);
    $expenses = AccountMetrics::expensesByMonth(2);

    expect($received)->toBe(['2026-09' => 1000.0, '2026-10' => 20000.0])
        ->and($expenses)->toBe(['2026-09' => 0.0, '2026-10' => 8000.0]);
});

it('works out pending collections across work streams and final projects', function () {
    seedFinances();

    // Work stream 50,000 − 15,000 received; project 20,000 − 6,000 received.
    expect(AccountMetrics::pendingDues())->toBe(['amount' => 49000.0, 'count' => 2]);
});

it('formats short rupee labels the Indian way', function () {
    expect(AccountMetrics::short(950))->toBe('₹950')
        ->and(AccountMetrics::short(12500))->toBe('₹12.5K')
        ->and(AccountMetrics::short(250000))->toBe('₹2.5 L')
        ->and(AccountMetrics::short(-3000))->toBe('-₹3K');
});

it('renders the dashboard with every widget for an admin', function () {
    seedFinances();
    $this->actingAs(User::factory()->admin()->create());

    $this->get('/accounts')->assertOk();

    foreach ([AccountStatsOverview::class, IncomeExpenseChart::class, ProfitChart::class, ExpenseCategoryChart::class,
        ServiceRevenueChart::class, ProposalsChart::class, EmployeeWorkChart::class] as $widget) {
        Livewire::test($widget)->assertOk();
    }

    Livewire::test(PendingCollectionsTable::class)
        ->assertOk()
        ->assertCanSeeTableRecords(Proposal::all());

    Livewire::test(AccountStatsOverview::class)
        ->assertSee('Received this month')
        ->assertSee('₹20K')
        ->assertSee('Net profit this month')
        ->assertSee('₹12K');
});

it('only shows staff the widgets their role allows', function () {
    $role = Role::create(['name' => 'Sales', 'permissions' => ['proposals' => ['view']]]);
    $this->actingAs(User::factory()->create(['role_id' => $role->id]));

    expect(ProposalsChart::canView())->toBeTrue()
        ->and(PendingCollectionsTable::canView())->toBeTrue()
        ->and(IncomeExpenseChart::canView())->toBeFalse()
        ->and(ExpenseCategoryChart::canView())->toBeFalse()
        ->and(EmployeeWorkChart::canView())->toBeFalse();

    Livewire::test(AccountStatsOverview::class)
        ->assertSee('Open proposals')
        ->assertDontSee('Expenses this month')
        ->assertDontSee('Monthly payroll');

    $this->get('/accounts')->assertOk();
});
