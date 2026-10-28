<?php

namespace App\Filament\Account\Resources\ProposalResource\Pages;

use App\Filament\Account\Resources\ProposalResource;
use App\Filament\Account\Forms\ReportFields;
use App\Models\AccountService;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Support\ProposalReport;
use Filament\Actions;
use Filament\Forms;
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
            static::reportAction(),
            Actions\CreateAction::make()->label('Create proposal'),
        ];
    }

    /** "Download report" — also opened from the Users list, preset to one person. */
    public static function reportAction(string $name = 'report'): Actions\Action
    {
        return Actions\Action::make($name)
            ->label('Download report')
            ->icon('heroicon-o-document-chart-bar')
            ->color('gray')
            ->visible(fn () => ProposalResource::userCan('view'))
            ->modalHeading('Proposal report')
            ->modalDescription('Proposals dated in the period, person-wise and service-wise totals, plus every payment received in it.')
            ->modalSubmitActionLabel('Download')
            ->modalWidth('lg')
            ->form([
                ...ReportFields::period(),
                Forms\Components\Select::make('creators')
                    ->label('Created by (person)')
                    ->placeholder('Everyone')
                    ->multiple()
                    ->searchable()
                    ->options(fn () => ProposalResource::creatorOptions()),
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
                ReportFields::format(),
            ])
            ->action(fn (array $data, $livewire) => $livewire->js(ReportFields::downloadJs('proposals.report', $data)));
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
