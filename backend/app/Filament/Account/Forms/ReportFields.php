<?php

namespace App\Filament\Account\Forms;

use App\Support\ReportPeriod;
use Filament\Forms;
use Filament\Forms\Get;

/** Period and format pickers shared by every "Download report" modal. */
class ReportFields
{
    public static function period(string $default = 'this_month'): array
    {
        return [
            Forms\Components\ToggleButtons::make('period')
                ->options(ReportPeriod::OPTIONS)
                ->default($default)
                ->inline()
                ->live()
                ->required(),
            Forms\Components\Grid::make(2)
                ->visible(fn (Get $get) => $get('period') === 'custom')
                ->schema([
                    Forms\Components\DatePicker::make('from')
                        ->default(now()->startOfMonth())
                        ->required(),
                    Forms\Components\DatePicker::make('to')
                        ->default(now())
                        ->afterOrEqual('from')
                        ->required(),
                ]),
        ];
    }

    public static function format(): Forms\Components\ToggleButtons
    {
        return Forms\Components\ToggleButtons::make('format')
            ->options(['pdf' => 'PDF', 'excel' => 'Excel'])
            ->icons(['pdf' => 'heroicon-o-document', 'excel' => 'heroicon-o-table-cells'])
            ->default('pdf')
            ->inline()
            ->required();
    }

    /** JS that starts the download of the report for the submitted modal data. */
    public static function downloadJs(string $route, array $data): string
    {
        return 'window.location.href = '.json_encode(route($route, array_filter($data))).';';
    }
}
