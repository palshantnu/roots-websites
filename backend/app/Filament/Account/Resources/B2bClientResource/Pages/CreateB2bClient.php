<?php

namespace App\Filament\Account\Resources\B2bClientResource\Pages;

use App\Filament\Account\Resources\B2bClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateB2bClient extends CreateRecord
{
    protected static string $resource = B2bClientResource::class;

    /** Straight to the client's page so projects can be added. */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
