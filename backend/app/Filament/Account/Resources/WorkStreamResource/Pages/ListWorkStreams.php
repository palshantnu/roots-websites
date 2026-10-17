<?php

namespace App\Filament\Account\Resources\WorkStreamResource\Pages;

use App\Filament\Account\Resources\WorkStreamResource;
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
