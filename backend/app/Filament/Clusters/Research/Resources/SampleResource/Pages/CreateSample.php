<?php

namespace App\Filament\Clusters\Research\Resources\SampleResource\Pages;

use App\Filament\Clusters\Research\Resources\SampleResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateSample extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = SampleResource::class;
}
