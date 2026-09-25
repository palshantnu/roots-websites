<?php

namespace App\Filament\Clusters\It\Resources\FaqResource\Pages;

use App\Filament\Clusters\It\Resources\FaqResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = FaqResource::class;
}
