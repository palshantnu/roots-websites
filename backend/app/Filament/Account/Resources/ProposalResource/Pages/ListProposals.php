<?php

namespace App\Filament\Account\Resources\ProposalResource\Pages;

use App\Filament\Account\Resources\ProposalResource;
use App\Models\Proposal;
use Filament\Actions;
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
