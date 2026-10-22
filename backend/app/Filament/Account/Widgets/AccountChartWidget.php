<?php

namespace App\Filament\Account\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

/**
 * Shared look for the Accounts dashboard charts: a validated colour-blind-safe
 * palette (fixed order — colour follows the series, never its rank), rounded
 * thin bars and ₹ axis/tooltip formatting.
 */
abstract class AccountChartWidget extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '280px';

    public const BLUE = '#2a78d6';

    public const ORANGE = '#eb6834';

    public const AQUA = '#1baf7a';

    protected static function allowed(string ...$modules): bool
    {
        $user = auth()->user();

        foreach ($modules as $module) {
            if (! $user?->hasAccountPermission($module, 'view')) {
                return false;
            }
        }

        return true;
    }

    protected function bar(string $label, array $data, string $color): array
    {
        return [
            'label' => $label,
            'data' => array_values($data),
            'backgroundColor' => $color,
            'borderColor' => $color,
            'borderRadius' => 4,
            'borderSkipped' => 'start',
            'maxBarThickness' => 28,
        ];
    }

    /** ₹ formatting (Indian grouping) on the value axis and in tooltips. */
    protected function moneyOptions(bool $horizontal = false, bool $legend = true, bool $stacked = false): RawJs
    {
        $value = $horizontal ? 'x' : 'y';
        $category = $horizontal ? 'y' : 'x';
        $indexAxis = $horizontal ? 'y' : 'x';
        $legend = $legend ? 'true' : 'false';
        $stacked = $stacked ? 'true' : 'false';

        return RawJs::make(<<<JS
            {
                indexAxis: '{$indexAxis}',
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: {$legend}, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' ' + ctx.dataset.label + ': ₹' + Number(ctx.parsed.{$value}).toLocaleString('en-IN'),
                        },
                    },
                },
                scales: {
                    {$value}: {
                        stacked: {$stacked},
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => {
                                const a = Math.abs(v);
                                const s = v < 0 ? '-₹' : '₹';
                                if (a >= 10000000) return s + (a / 10000000) + 'Cr';
                                if (a >= 100000) return s + (a / 100000) + 'L';
                                if (a >= 1000) return s + (a / 1000) + 'K';
                                return s + a;
                            },
                        },
                    },
                    {$category}: { stacked: {$stacked}, grid: { display: false } },
                },
            }
        JS);
    }
}
