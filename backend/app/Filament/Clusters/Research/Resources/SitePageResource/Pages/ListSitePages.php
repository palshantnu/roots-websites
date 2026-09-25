<?php

namespace App\Filament\Clusters\Research\Resources\SitePageResource\Pages;

use App\Filament\Clusters\Research\Resources\SitePageResource;
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
