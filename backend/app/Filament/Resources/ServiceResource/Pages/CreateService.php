<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Concerns\CreatesSiteRecords;
use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = ServiceResource::class;
}
