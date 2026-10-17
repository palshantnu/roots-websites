<?php

namespace App\Filament\Account\Resources\AccountServiceResource\Pages;

use App\Filament\Account\Resources\AccountServiceResource;
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
