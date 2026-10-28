<?php

namespace App\Filament\Account\Widgets;

use App\Models\B2bClient;
use Filament\Support\RawJs;

class B2bClientsChart extends AccountChartWidget
{
    protected static ?string $heading = 'Top B2B clients';

    protected static ?string $description = 'Total business vs received, top 8 clients';

    protected static ?int $sort = 9;

    public static function canView(): bool
    {
        return static::allowed('b2b_projects');
    }

    protected function getData(): array
    {
        $clients = B2bClient::withTotals()
            ->get()
            ->filter(fn (B2bClient $c) => $c->business > 0)
            ->sortByDesc('business')
            ->take(8);

        return [
            'labels' => $clients->pluck('company_name')->map(fn ($name) => str($name)->limit(22)->toString())->values()->all(),
            'datasets' => [
                $this->bar('Business', $clients->map->business->all(), self::BLUE),
                $this->bar('Received', $clients->map->received->all(), self::AQUA),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): RawJs
    {
        return $this->moneyOptions(horizontal: true);
    }
}
