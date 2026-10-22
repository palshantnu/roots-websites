<?php

namespace App\Filament\Account\Widgets;

use App\Models\Proposal;
use App\Support\AccountMetrics;

class ProposalsChart extends AccountChartWidget
{
    protected static ?string $heading = 'Proposals sent vs finalised';

    protected static ?string $description = 'Count per month, last 6 months';

    protected static ?int $sort = 6;

    public static function canView(): bool
    {
        return static::allowed('proposals');
    }

    protected function getData(): array
    {
        $months = AccountMetrics::months(6);
        $from = $months->first();
        $empty = $months->mapWithKeys(fn ($m) => [$m->format('Y-m') => 0])->all();

        $sent = $empty;
        foreach (Proposal::where('proposal_date', '>=', $from->toDateString())->pluck('proposal_date') as $date) {
            $sent[$date->format('Y-m')] = ($sent[$date->format('Y-m')] ?? 0) + 1;
        }

        $final = $empty;
        foreach (Proposal::where('finalized_at', '>=', $from)->pluck('finalized_at') as $date) {
            $final[$date->format('Y-m')] = ($final[$date->format('Y-m')] ?? 0) + 1;
        }

        return [
            'labels' => $months->map->format('M y')->all(),
            'datasets' => [
                $this->bar('Sent', array_intersect_key($sent, $empty), self::BLUE),
                $this->bar('Finalised', array_intersect_key($final, $empty), self::AQUA),
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
            'plugins' => ['legend' => ['display' => true, 'position' => 'bottom', 'labels' => ['usePointStyle' => true, 'boxWidth' => 8]]],
            'interaction' => ['mode' => 'index', 'intersect' => false],
            'scales' => [
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
                'x' => ['grid' => ['display' => false]],
            ],
        ];
    }
}
