<?php

namespace App\Filament\Account\Resources\ProposalResource\Pages;

use App\Filament\Account\Resources\ProposalResource;
use App\Models\AccountService;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Support\ProposalReport;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProposals extends ListRecords
{
    protected static string $resource = ProposalResource::class;

    public function mount(): void
    {
        parent::mount();

        // Right after "Create", start the PDF download of the new proposal.
        $download = Proposal::find(request()->integer('download'));

        if ($download) {
            $this->js(
                'history.replaceState(null, "", location.pathname);'.
                'window.location.href = '.json_encode(route('proposals.pdf', $download)).';'
            );
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('report')
                ->label('Download report')
                ->icon('heroicon-o-document-chart-bar')
                ->color('gray')
                ->modalHeading('Proposal report')
                ->modalDescription('Proposals dated in the period, plus every payment received in it.')
                ->modalSubmitActionLabel('Download')
                ->modalWidth('lg')
                ->form([
                    Forms\Components\ToggleButtons::make('period')
                        ->options(ProposalReport::PERIODS)
                        ->default('this_month')
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
                    Forms\Components\Select::make('company')
                        ->placeholder('All companies')
                        ->options(fn () => ProposalCompany::orderBy('sort_order')->pluck('name', 'id')),
                    Forms\Components\Select::make('services')
                        ->label('Service')
                        ->placeholder('All services')
                        ->multiple()
                        ->options(fn () => AccountService::orderBy('sort_order')->pluck('name', 'id')),
                    Forms\Components\Select::make('status')
                        ->options(ProposalReport::STATUSES)
                        ->default('all')
                        ->selectablePlaceholder(false),
                    Forms\Components\ToggleButtons::make('format')
                        ->options(['pdf' => 'PDF', 'excel' => 'Excel'])
                        ->icons(['pdf' => 'heroicon-o-document', 'excel' => 'heroicon-o-table-cells'])
                        ->default('pdf')
                        ->inline()
                        ->required(),
                ])
                ->action(fn (array $data) => $this->js(
                    'window.location.href = '.json_encode(route('proposals.report', array_filter($data))).';'
                )),
            Actions\CreateAction::make()->label('Create proposal'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'proposals' => Tab::make('Proposals')
                ->icon('heroicon-o-document-text')
                ->badge(fn () => Proposal::where('status', Proposal::STATUS_PROPOSAL)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Proposal::STATUS_PROPOSAL)),
            'final' => Tab::make('Final projects')
                ->icon('heroicon-o-check-badge')
                ->badge(fn () => Proposal::where('status', Proposal::STATUS_FINAL)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Proposal::STATUS_FINAL)),
        ];
    }
}
