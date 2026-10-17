<?php

namespace App\Filament\Account\Resources\AccountServiceResource\Pages;

use App\Filament\Account\Resources\AccountServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountServices extends ListRecords
{
    protected static string $resource = AccountServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
