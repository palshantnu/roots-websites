<?php

namespace App\Filament\Clusters\AccountManagement\Resources\AccountServiceResource\Pages;

use App\Filament\Clusters\AccountManagement\Resources\AccountServiceResource;
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
