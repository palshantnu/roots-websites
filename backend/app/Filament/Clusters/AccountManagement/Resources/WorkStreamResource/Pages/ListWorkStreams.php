<?php

namespace App\Filament\Clusters\AccountManagement\Resources\WorkStreamResource\Pages;

use App\Filament\Clusters\AccountManagement\Resources\WorkStreamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkStreams extends ListRecords
{
    protected static string $resource = WorkStreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
