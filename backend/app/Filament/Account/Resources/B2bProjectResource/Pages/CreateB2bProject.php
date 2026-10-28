<?php

namespace App\Filament\Account\Resources\B2bProjectResource\Pages;

use App\Filament\Account\Resources\B2bProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateB2bProject extends CreateRecord
{
    protected static string $resource = B2bProjectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
