<?php

namespace App\Filament\Account\Resources\MarketingExpenseResource\Pages;

use App\Filament\Account\Forms\ReportFields;
use App\Filament\Account\Resources\MarketingExpenseResource;
use App\Models\AccountService;
use App\Models\MarketingChannel;
use App\Models\MarketingExpense;
use App\Models\ProposalCompany;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Number;

class ListMarketingExpenses extends ListRecords
{
    protected static string $resource = MarketingExpenseResource::class;

    public function getSubheading(): ?string
    {
        $spent = fn ($from) => Number::currency((float) MarketingExpense::where('spent_on', '>=', $from->toDateString())->sum('amount'), 'INR');

        return 'This month '.$spent(now()->startOfMonth()).' · This year '.$spent(now()->startOfYear());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('report')
                ->label('Download report')
                ->icon('heroicon-o-document-chart-bar')
                ->color('gray')
                ->modalHeading('Marketing expense report')
                ->modalDescription('Spend in the period by channel, person / agency, campaign, service and month.')
                ->modalSubmitActionLabel('Download')
                ->modalWidth('lg')
                ->form([
                    ...ReportFields::period(),
                    Forms\Components\Select::make('channels')
                        ->label('Channel')
                        ->placeholder('All channels')
                        ->multiple()
                        ->options(fn () => MarketingChannel::orderBy('sort_order')->pluck('name', 'id')),
                    Forms\Components\Select::make('services')
                        ->label('Service')
                        ->placeholder('All services')
                        ->multiple()
                        ->options(fn () => AccountService::orderBy('sort_order')->pluck('name', 'id')),
                    Forms\Components\Select::make('company')
                        ->placeholder('All companies')
                        ->options(fn () => ProposalCompany::orderBy('sort_order')->pluck('name', 'id')),
                    ReportFields::format(),
                ])
                ->action(fn (array $data) => $this->js(ReportFields::downloadJs('marketing.report', $data))),
            Actions\CreateAction::make()->label('Add expense'),
        ];
    }
}
