<?php

namespace App\Filament\Account\Widgets;

use App\Models\AccountService;
use Filament\Support\RawJs;

class ServiceRevenueChart extends AccountChartWidget
{
    protected static ?string $heading = 'Work value by service';

    protected static ?string $description = 'Total cost of work streams in each line of business';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return static::allowed('work_streams');
    }

    protected function getData(): array
    {
        $services = AccountService::withSum('workStreams', 'cost')
            ->get()
            ->filter(fn ($s) => $s->work_streams_sum_cost > 0)
            ->sortByDesc('work_streams_sum_cost');

        return [
            'labels' => $services->pluck('name')->all(),
            'datasets' => [$this->bar('Work value', $services->pluck('work_streams_sum_cost')->map(fn ($v) => (float) $v)->all(), self::BLUE)],
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
