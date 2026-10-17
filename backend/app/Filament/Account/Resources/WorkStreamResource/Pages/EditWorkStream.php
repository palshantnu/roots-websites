<?php

namespace App\Filament\Account\Resources\WorkStreamResource\Pages;

use App\Filament\Account\Resources\WorkStreamResource;
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
