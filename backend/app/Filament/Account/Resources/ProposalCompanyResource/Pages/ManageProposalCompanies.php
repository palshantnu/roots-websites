<?php

namespace App\Filament\Account\Resources\ProposalCompanyResource\Pages;

use App\Filament\Account\Resources\ProposalCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProposalCompanies extends ManageRecords
{
    protected static string $resource = ProposalCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
