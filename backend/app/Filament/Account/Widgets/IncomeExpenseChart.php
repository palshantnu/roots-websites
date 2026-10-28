<?php

namespace App\Filament\Account\Widgets;

use App\Support\AccountMetrics;
use Filament\Support\RawJs;

class IncomeExpenseChart extends AccountChartWidget
{
    protected static ?string $heading = 'Received vs expenses';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '12';

    public static function canView(): bool
    {
        return static::allowed('expenses') && (static::allowed('work_streams') || static::allowed('proposals') || static::allowed('b2b_projects'));
    }

    protected function getFilters(): ?array
    {
        return ['6' => 'Last 6 months', '12' => 'Last 12 months', '24' => 'Last 24 months'];
    }

    protected function getData(): array
    {
        $months = (int) $this->filter;

        return [
            'labels' => AccountMetrics::months($months)->map->format('M y')->all(),
            'datasets' => [
                $this->bar('Received', AccountMetrics::receivedByMonth($months), self::BLUE),
                $this->bar('Expenses', AccountMetrics::expensesByMonth($months), self::ORANGE),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return $this->moneyOptions();
    }
}
