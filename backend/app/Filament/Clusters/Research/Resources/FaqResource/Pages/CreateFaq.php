<?php

namespace App\Filament\Clusters\Research\Resources\FaqResource\Pages;

use App\Filament\Clusters\Research\Resources\FaqResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = FaqResource::class;
}
