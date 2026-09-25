<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Concerns\CreatesSiteRecords;
use App\Filament\Resources\FaqResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = FaqResource::class;
}
