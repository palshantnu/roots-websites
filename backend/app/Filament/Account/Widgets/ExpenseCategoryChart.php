<?php

namespace App\Filament\Account\Widgets;

use App\Models\Expense;
use Filament\Support\RawJs;

class ExpenseCategoryChart extends AccountChartWidget
{
    protected static ?string $heading = 'Where the money goes';

    protected static ?int $sort = 4;

    public ?string $filter = 'year';

    public static function canView(): bool
    {
        return static::allowed('expenses');
    }

    protected function getFilters(): ?array
    {
        return ['month' => 'This month', 'year' => 'This year', 'all' => 'All time'];
    }

    protected function getData(): array
    {
        $totals = Expense::query()
            ->when($this->filter === 'month', fn ($q) => $q->where('expense_date', '>=', now()->startOfMonth()->toDateString()))
            ->when($this->filter === 'year', fn ($q) => $q->where('expense_date', '>=', now()->startOfYear()->toDateString()))
            ->get(['category', 'amount'])
            ->groupBy(fn ($e) => $e->category ?: 'Other')
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
