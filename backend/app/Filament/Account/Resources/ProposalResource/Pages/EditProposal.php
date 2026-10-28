<?php

namespace App\Filament\Account\Resources\ProposalResource\Pages;

use App\Filament\Account\Resources\ProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProposal extends EditRecord
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(fn () => route('proposals.pdf', $this->getRecord())),
            ProposalResource::invoiceAction(Actions\Action::make('invoice')),
            Actions\DeleteAction::make(),
        ];
    }
}
