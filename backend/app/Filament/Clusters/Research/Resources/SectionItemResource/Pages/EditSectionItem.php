<?php

namespace App\Filament\Clusters\Research\Resources\SectionItemResource\Pages;

use App\Filament\Clusters\Research\Resources\SectionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSectionItem extends EditRecord
{
    protected static string $resource = SectionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
