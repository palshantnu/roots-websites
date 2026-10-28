<?php

use App\Filament\Account\Resources\MarketingChannelResource\Pages\ListMarketingChannels;
use App\Filament\Account\Resources\MarketingExpenseResource\Pages\CreateMarketingExpense;
use App\Filament\Account\Resources\MarketingExpenseResource\Pages\ListMarketingExpenses;
use App\Filament\Account\Widgets\AccountStatsOverview;
use App\Filament\Account\Widgets\ExpenseCategoryChart;
use App\Filament\Account\Widgets\MarketingSpendChart;
use App\Models\AccountService;
use App\Models\Expense;
use App\Models\MarketingChannel;
use App\Models\MarketingExpense;
use App\Models\Role;
use App\Models\User;
use App\Support\AccountMetrics;
use App\Support\MarketingReport;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('account'));
    $this->travelTo(now()->setDate(2026, 10, 15));
});

function channel(string $name): MarketingChannel
{
    return MarketingChannel::where('name', $name)->firstOrFail();
}

function spend(string $channel, float $amount, array $extra = []): MarketingExpense
{
    return MarketingExpense::create($extra + [
        'marketing_channel_id' => channel($channel)->id,
        'title' => "{$channel} spend",
        'amount' => $amount,
        'spent_on' => now(),
    ]);
}

it('seeds the common marketing channels', function () {
    expect(MarketingChannel::orderBy('sort_order')->pluck('name')->all())
        ->toContain('Google Ads', 'Meta Ads (Facebook / Instagram)', 'Freelancer / Influencer', 'Marketing agency');
});

it('opens the marketing pages for an admin', function () {
    $expense = spend('Google Ads', 5000);
    $admin = User::factory()->admin()->create();

    foreach ([
        '/accounts/marketing-expenses', '/accounts/marketing-expenses/create', "/accounts/marketing-expenses/{$expense->id}/edit",
        '/accounts/marketing-channels', '/accounts/marketing-channels/create',
    ] as $page) {
        $this->actingAs($admin)->get($page)->assertOk();
    }
});

it('records an ad payment with its receipt and who added it', function () {
    Storage::fake('public');
    $this->actingAs($user = User::factory()->admin()->create());

    Livewire::test(CreateMarketingExpense::class)
        ->fillForm([
            'title' => 'Search ads — thesis keywords',
            'marketing_channel_id' => channel('Google Ads')->id,
            'paid_to' => 'Google India',
            'campaign' => 'Diwali 2026',
            'account_service_id' => AccountService::firstOrCreate(['name' => 'Research'])->id,
            'amount' => 12000,
            'spent_on' => now()->toDateString(),
            'leads' => 30,
            'receipt' => UploadedFile::fake()->create('bill.pdf', 100, 'application/pdf'),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $expense = MarketingExpense::sole();
    expect($expense->created_by)->toBe($user->id)
        ->and($expense->receipt)->toStartWith('marketing-receipts/')
        ->and(MarketingExpense::costPerLead((float) $expense->amount, $expense->leads))->toBe(400.0);
    Storage::disk('public')->assertExists($expense->receipt);
});

it('records a payment to a person for growing the business', function () {
    $this->actingAs(User::factory()->admin()->create());

    Livewire::test(CreateMarketingExpense::class)
        ->fillForm([
            'title' => 'Instagram reels for October',
            'marketing_channel_id' => channel('Freelancer / Influencer')->id,
            'paid_to' => 'Rohit (freelancer)',
            'amount' => 8000,
            'spent_on' => now()->toDateString(),
            'mode' => 'UPI',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(MarketingExpense::sole()->paid_to)->toBe('Rohit (freelancer)');
});

it('filters marketing expenses by channel and person paid', function () {
    $this->actingAs(User::factory()->admin()->create());
    $google = spend('Google Ads', 5000, ['paid_to' => 'Google India']);
    $rohit = spend('Freelancer / Influencer', 3000, ['paid_to' => 'Rohit']);

    Livewire::test(ListMarketingExpenses::class)
        ->assertSee('This month')
        ->filterTable('marketing_channel_id', [channel('Google Ads')->id])
        ->assertCanSeeTableRecords([$google])
        ->assertCanNotSeeTableRecords([$rohit])
        ->resetTableFilters()
        ->filterTable('paid_to', 'Rohit')
        ->assertCanSeeTableRecords([$rohit])
        ->assertCanNotSeeTableRecords([$google]);
});

it('adds a new channel and stops deleting channels that have spend', function () {
    $this->actingAs(User::factory()->admin()->create());
    spend('Google Ads', 1000);
    $unused = MarketingChannel::create(['name' => 'Justdial', 'type' => 'platform']);

    Livewire::test(ListMarketingChannels::class)
        ->assertTableActionHidden('delete', channel('Google Ads'))
        ->assertTableActionVisible('delete', $unused);
});

it('builds the report by channel, person, campaign and month', function () {
    spend('Google Ads', 6000, ['campaign' => 'Diwali', 'leads' => 20]);
    spend('Google Ads', 4000, ['campaign' => 'Diwali', 'leads' => 5, 'spent_on' => '2026-09-20']);
    spend('Freelancer / Influencer', 5000, ['paid_to' => 'Rohit']);
    spend('Marketing agency', 9000, ['paid_to' => 'GrowFast Agency', 'spent_on' => '2026-08-01']);

    $month = MarketingReport::fromArray(['period' => 'this_month']);
    expect($month->totals())->toMatchArray([
        'Total spent' => 11000.0, 'Entries' => 2, 'Leads' => 20, 'Cost per lead' => 550.0, 'Top channel' => 'Google Ads',
    ])
        ->and($month->byPaidTo()->pluck('name')->all())->toBe(['Rohit']);

    $year = MarketingReport::fromArray(['period' => 'this_year']);
    $google = $year->byChannel()->firstWhere('name', 'Google Ads');
    expect($google)->toMatchArray(['entries' => 2, 'amount' => 10000.0, 'leads' => 25, 'cost_per_lead' => 400.0, 'share' => 42])
        ->and($year->byCampaign()->first())->toMatchArray(['name' => 'Diwali', 'amount' => 10000.0])
        ->and($year->byPaidTo()->first()['name'])->toBe('GrowFast Agency')
        ->and($year->byMonth()->pluck('name')->all())->toBe(['Aug 2026', 'Sep 2026', 'Oct 2026']);

    $onlyGoogle = MarketingReport::fromArray(['period' => 'this_year', 'channels' => [channel('Google Ads')->id]]);
    expect($onlyGoogle->expenses)->toHaveCount(2);
});

it('downloads the marketing report as PDF and Excel', function () {
    spend('Google Ads', 6000, ['leads' => 12]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('marketing.report', ['period' => 'this_month', 'format' => 'pdf']))
        ->assertOk()->assertDownload('marketing-report-2026-10-01-to-2026-10-31.pdf');

    $this->actingAs($admin)->get(route('marketing.report', ['period' => 'this_month', 'format' => 'excel']))
        ->assertOk()->assertDownload('marketing-report-2026-10-01-to-2026-10-31.xlsx');

    $this->actingAs($admin);
    Livewire::test(ListMarketingExpenses::class)
        ->callAction('report', ['period' => 'this_year', 'format' => 'excel'])
        ->assertHasNoActionErrors();
});

it('keeps marketing away from roles without marketing access', function () {
    $role = Role::create(['name' => 'Sales', 'permissions' => ['proposals' => ['view']]]);
    $user = User::factory()->create(['role_id' => $role->id]);

    $this->actingAs($user)->get('/accounts/marketing-expenses')->assertForbidden();
    $this->actingAs($user)->get(route('marketing.report'))->assertForbidden();
});

it('counts marketing spend in the dashboard expenses and profit', function () {
    $this->actingAs(User::factory()->admin()->create());
    Expense::create(['title' => 'Rent', 'category' => 'Office & Rent', 'amount' => 20000, 'expense_date' => now()]);
    spend('Google Ads', 7000);

    expect(AccountMetrics::expensesByMonth(1)['2026-10'])->toBe(27000.0);

    Livewire::test(AccountStatsOverview::class)->assertSee('Marketing this month');
    Livewire::test(ExpenseCategoryChart::class)->assertOk();
    Livewire::test(MarketingSpendChart::class)->assertOk();
});
