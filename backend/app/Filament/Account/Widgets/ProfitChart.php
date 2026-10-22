<?php

namespace App\Filament\Account\Widgets;

use App\Support\AccountMetrics;
use Filament\Support\RawJs;

class ProfitChart extends AccountChartWidget
{
    protected static ?string $heading = 'Net profit by month';

    protected static ?string $description = 'Received minus expenses, last 12 months';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return IncomeExpenseChart::canView();
    }

    protected function getData(): array
    {
        $received = AccountMetrics::receivedByMonth(12);
        $expenses = AccountMetrics::expensesByMonth(12);

        return [
            'labels' => AccountMetrics::months(12)->map->format('M y')->all(),
            'datasets' => [[
                'label' => 'Net profit',
                'data' => array_values(array_map(fn ($r, $e) => round($r - $e, 2), $received, $expenses)),
                'borderColor' => self::BLUE,
                'backgroundColor' => self::BLUE,
                'borderWidth' => 2,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
                'tension' => 0.3,
            ]],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return $this->moneyOptions(legend: false);
    }
}
