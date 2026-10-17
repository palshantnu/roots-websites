<?php

namespace App\Filament\Clusters\AccountManagement\Resources\WorkStreamResource\Pages;

use App\Filament\Clusters\AccountManagement\Resources\WorkStreamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkStream extends EditRecord
{
    protected static string $resource = WorkStreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
