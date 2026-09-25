<?php

namespace App\Filament\Clusters\It\Resources\SitePageResource\Pages;

use App\Filament\Clusters\It\Resources\SitePageResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateSitePage extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = SitePageResource::class;
}
