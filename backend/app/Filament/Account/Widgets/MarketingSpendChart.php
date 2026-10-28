<?php

namespace App\Filament\Account\Widgets;

use App\Models\MarketingExpense;
use Filament\Support\RawJs;

class MarketingSpendChart extends AccountChartWidget
{
    protected static ?string $heading = 'Marketing spend by channel';

    protected static ?int $sort = 11;

    public ?string $filter = 'month';

    public static function canView(): bool
    {
        return static::allowed('marketing');
    }

    protected function getFilters(): ?array
    {
        return ['month' => 'This month', 'year' => 'This year', 'all' => 'All time'];
    }

    protected function getData(): array
    {
        $totals = MarketingExpense::query()
            ->with('channel')
            ->when($this->filter === 'month', fn ($q) => $q->where('spent_on', '>=', now()->startOfMonth()->toDateString()))
            ->when($this->filter === 'year', fn ($q) => $q->where('spent_on', '>=', now()->startOfYear()->toDateString()))
            ->get()
            ->groupBy(fn (MarketingExpense $e) => $e->channel?->name ?? '—')
            ->map(fn ($rows) => round((float) $rows->sum('amount'), 2))
            ->sortDesc();

        return [
            'labels' => $totals->keys()->all(),
            'datasets' => [$this->bar('Spent', $totals->all(), self::ORANGE)],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return $this->moneyOptions(horizontal: true, legend: false);
    }
}
