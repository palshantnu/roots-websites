<?php

namespace App\Filament\Clusters\Research\Resources\SampleResource\Pages;

use App\Filament\Clusters\Research\Resources\SampleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSample extends EditRecord
{
    protected static string $resource = SampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
