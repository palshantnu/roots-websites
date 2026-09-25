<?php

namespace App\Filament\Clusters\It\Resources\SitePageResource\Pages;

use App\Filament\Clusters\It\Resources\SitePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSitePages extends ListRecords
{
    protected static string $resource = SitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
