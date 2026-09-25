<?php

namespace App\Filament\Clusters\It\Resources\CaseStudyResource\Pages;

use App\Filament\Clusters\It\Resources\CaseStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaseStudies extends ListRecords
{
    protected static string $resource = CaseStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
