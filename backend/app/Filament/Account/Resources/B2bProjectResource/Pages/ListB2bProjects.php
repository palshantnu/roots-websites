<?php

namespace App\Filament\Account\Resources\B2bProjectResource\Pages;

use App\Filament\Account\Forms\ReportFields;
use App\Filament\Account\Resources\B2bProjectResource;
use App\Models\AccountService;
use App\Models\B2bClient;
use App\Models\B2bProject;
use App\Models\ProposalCompany;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListB2bProjects extends ListRecords
{
    protected static string $resource = B2bProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            static::reportAction(),
            Actions\CreateAction::make()->label('New project'),
        ];
    }

    /** "Download report" — business report for a period; also on the clients list. */
    public static function reportAction(): Actions\Action
    {
        return Actions\Action::make('report')
            ->label('Download report')
            ->icon('heroicon-o-document-chart-bar')
            ->color('gray')
            ->visible(fn () => B2bProjectResource::userCan('view'))
            ->modalHeading('B2B business report')
            ->modalDescription('Projects started in the period, client-wise and service-wise business, and every payment received in it. Pick a client for its statement.')
            ->modalSubmitActionLabel('Download')
            ->modalWidth('lg')
            ->form([
                ...ReportFields::period(),
                Forms\Components\Select::make('client')
                    ->placeholder('All clients')
                    ->options(fn () => B2bClient::orderBy('company_name')->pluck('company_name', 'id'))
                    ->searchable(),
                Forms\Components\Select::make('services')
                    ->label('Service')
                    ->placeholder('All services')
                    ->multiple()
                    ->options(fn () => AccountService::orderBy('sort_order')->pluck('name', 'id')),
                Forms\Components\Select::make('company')
                    ->label('Billed by')
                    ->placeholder('All companies')
                    ->options(fn () => ProposalCompany::orderBy('sort_order')->pluck('name', 'id')),
                Forms\Components\Select::make('status')
                    ->placeholder('All statuses')
                    ->options(B2bProject::STATUSES),
                ReportFields::format(),
            ])
            ->action(fn (array $data, $livewire) => $livewire->js(ReportFields::downloadJs('b2b.report', $data)));
    }

    public function getTabs(): array
    {
        $ongoing = ['pending', 'in_progress', 'on_hold'];

        return [
            'all' => Tab::make('All'),
            'ongoing' => Tab::make('Ongoing')
                ->icon('heroicon-o-arrow-path')
                ->badge(fn () => B2bProject::whereIn('status', $ongoing)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', $ongoing)),
            'completed' => Tab::make('Completed')
                ->icon('heroicon-o-check-badge')
                ->badge(fn () => B2bProject::where('status', 'completed')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed')),
            'cancelled' => Tab::make('Cancelled')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled')),
        ];
    }
}
