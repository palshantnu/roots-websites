<?php

namespace App\Filament\Clusters\It\Resources\CaseStudyResource\Pages;

use App\Filament\Clusters\It\Resources\CaseStudyResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreateCaseStudy extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = CaseStudyResource::class;
}
