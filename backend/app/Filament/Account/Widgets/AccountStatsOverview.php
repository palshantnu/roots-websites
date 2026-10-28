<?php

namespace App\Filament\Account\Widgets;

use App\Models\B2bClient;
use App\Models\B2bProject;
use App\Models\Employee;
use App\Models\Proposal;
use App\Support\AccountMetrics;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** Headline numbers for this month, each with a 6-month sparkline. */
class AccountStatsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        $user = auth()->user();

        return collect(['work_streams', 'proposals', 'b2b_projects', 'expenses', 'marketing', 'employees'])
            ->contains(fn ($module) => $user?->hasAccountPermission($module, 'view'));
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $can = fn (string $module) => $user->hasAccountPermission($module, 'view');
        $stats = [];

        $seesIncome = $can('work_streams') || $can('proposals') || $can('b2b_projects');
        $received = $seesIncome ? array_values(AccountMetrics::receivedByMonth(6)) : [];
        $expenses = $can('expenses') ? array_values(AccountMetrics::expensesByMonth(6)) : [];

        if ($seesIncome) {
            $stats[] = $this->monthStat('Received this month', $received, 'success', 'heroicon-m-arrow-trending-up');
        }

        if ($expenses) {
            $stats[] = $this->monthStat('Expenses this month', $expenses, 'danger', 'heroicon-m-arrow-trending-down');
        }

        if ($seesIncome && $expenses) {
            $profit = array_map(fn ($r, $e) => $r - $e, $received, $expenses);
            $now = end($profit);

            $stats[] = Stat::make('Net profit this month', AccountMetrics::short($now))
                ->description($now >= 0 ? 'Profit' : 'Loss')
                ->descriptionIcon($now >= 0 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                ->chart($profit)
                ->color($now >= 0 ? 'success' : 'danger');
        }

        if ($seesIncome) {
            $dues = AccountMetrics::pendingDues();

            $stats[] = Stat::make('Pending collections', AccountMetrics::short($dues['amount']))
                ->description($dues['count'].' '.str('project')->plural($dues['count']).' with money due')
                ->descriptionIcon('heroicon-m-clock')
                ->color($dues['amount'] > 0 ? 'warning' : 'success');
        }

        if ($can('proposals')) {
            $open = Proposal::where('status', Proposal::STATUS_PROPOSAL);
            $total = Proposal::count();
            $final = Proposal::where('status', Proposal::STATUS_FINAL)->count();

            $stats[] = Stat::make('Open proposals', $open->count())
                ->description(AccountMetrics::short((float) $open->sum('total_amount')).' quoted · '
                    .($total ? round($final / $total * 100) : 0).'% converted')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary');
        }

        if ($can('b2b_projects')) {
            $clients = B2bClient::where('is_active', true)->count();
            $ongoing = B2bProject::whereIn('status', ['pending', 'in_progress', 'on_hold']);

            $stats[] = Stat::make('B2B business', AccountMetrics::short((float) B2bProject::where('status', '!=', 'cancelled')->sum('value')))
                ->description($clients.' active '.str('client')->plural($clients).' · '.$ongoing->count().' ongoing')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info');
        }

        if ($can('marketing')) {
            $marketing = array_values(AccountMetrics::bucketed(AccountMetrics::marketingByDay(
                AccountMetrics::months(6)->first(), now()->endOfMonth()
            ), 6));

            $stats[] = $this->monthStat('Marketing this month', $marketing, 'warning', 'heroicon-m-megaphone');
        }

        if ($can('employees')) {
            $active = Employee::where('is_active', true);

            $stats[] = Stat::make('Monthly payroll', AccountMetrics::short((float) $active->sum('monthly_salary')))
                ->description($active->count().' active '.str('employee')->plural($active->count()))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray');
        }

        return $stats;
    }

    private function monthStat(string $label, array $series, string $color, string $icon): Stat
    {
        $now = (float) end($series);
        $before = (float) ($series[count($series) - 2] ?? 0);
        $change = $before > 0 ? round(($now - $before) / $before * 100) : null;

        return Stat::make($label, AccountMetrics::short($now))
            ->description($change === null ? 'Last 6 months' : ($change >= 0 ? '+' : '').$change.'% vs last month')
            ->descriptionIcon($icon)
            ->chart($series)
            ->color($color);
    }
}
