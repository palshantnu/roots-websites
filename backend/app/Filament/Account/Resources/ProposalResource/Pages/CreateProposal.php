<?php

namespace App\Filament\Account\Resources\ProposalResource\Pages;

use App\Filament\Account\Resources\ProposalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProposal extends CreateRecord
{
    protected static string $resource = ProposalResource::class;

    protected static bool $canCreateAnother = false;

    /** Back to the list, which then downloads the new proposal's PDF. */
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index', ['download' => $this->getRecord()->getKey()]);
    }
}
