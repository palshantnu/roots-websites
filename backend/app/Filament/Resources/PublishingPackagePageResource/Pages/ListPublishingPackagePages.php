<?php

namespace App\Filament\Resources\PublishingPackagePageResource\Pages;

use App\Filament\Resources\PublishingPackagePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPublishingPackagePages extends ListRecords
{
    protected static string $resource = PublishingPackagePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
