<?php

namespace App\Filament\Clusters\It\Resources\SectionItemResource\Pages;

use App\Filament\Clusters\It\Resources\SectionItemResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateSectionItem extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = SectionItemResource::class;
}
