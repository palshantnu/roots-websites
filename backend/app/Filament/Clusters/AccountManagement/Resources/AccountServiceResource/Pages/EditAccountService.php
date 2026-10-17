<?php

namespace App\Filament\Clusters\AccountManagement\Resources\AccountServiceResource\Pages;

use App\Filament\Clusters\AccountManagement\Resources\AccountServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountService extends EditRecord
{
    protected static string $resource = AccountServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
