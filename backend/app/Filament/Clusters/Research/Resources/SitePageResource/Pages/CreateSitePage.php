<?php

namespace App\Filament\Clusters\Research\Resources\SitePageResource\Pages;

use App\Filament\Clusters\Research\Resources\SitePageResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateSitePage extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = SitePageResource::class;
}
