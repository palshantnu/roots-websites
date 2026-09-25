<?php

namespace App\Filament\Clusters\It\Resources\ProjectResource\Pages;

use App\Filament\Clusters\It\Resources\ProjectResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = ProjectResource::class;
}
