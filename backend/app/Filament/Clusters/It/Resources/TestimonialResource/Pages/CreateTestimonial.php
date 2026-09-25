<?php

namespace App\Filament\Clusters\It\Resources\TestimonialResource\Pages;

use App\Filament\Clusters\It\Resources\TestimonialResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = TestimonialResource::class;
}
