<?php

namespace App\Filament\Clusters\Research\Resources\ServiceResource\Pages;

use App\Filament\Clusters\Research\Resources\ServiceResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = ServiceResource::class;
}
