<?php

namespace App\Filament\Account\Widgets;

use App\Models\Employee;

class EmployeeWorkChart extends AccountChartWidget
{
    protected static ?string $heading = 'Work by employee';

    protected static ?string $description = 'Projects assigned to each active employee, by status';

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return static::allowed('employees');
    }

    protected function getData(): array
    {
        $employees = Employee::where('is_active', true)
            ->withCount([
                'workStreams as completed' => fn ($q) => $q->where('status', 'completed'),
                'workStreams as in_progress' => fn ($q) => $q->where('status', 'in_progress'),
                'workStreams as pending' => fn ($q) => $q->where('status', 'pending'),
            ])
            ->get()
            ->sortByDesc(fn ($e) => $e->completed + $e->in_progress + $e->pending)
            ->values();

        $series = fn (string $label, string $key, string $color) => [
            ...$this->bar($label, $employees->pluck($key)->all(), $color),
            'borderColor' => 'transparent',
            'borderWidth' => 1,
        ];

        return [
            'labels' => $employees->pluck('name')->all(),
            'datasets' => [
                $series('Completed', 'completed', self::AQUA),
                $series('In progress', 'in_progress', self::BLUE),
                $series('Pending', 'pending', self::ORANGE),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => true, 'position' => 'bottom', 'labels' => ['usePointStyle' => true, 'boxWidth' => 8]]],
            'interaction' => ['mode' => 'index', 'intersect' => false],
            'scales' => [
                'x' => ['stacked' => true, 'beginAtZero' => true, 'ticks' => ['precision' => 0]],
                'y' => ['stacked' => true, 'grid' => ['display' => false]],
            ],
        ];
    }
}
