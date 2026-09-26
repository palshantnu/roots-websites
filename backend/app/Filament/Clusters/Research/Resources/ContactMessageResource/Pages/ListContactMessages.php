<?php

namespace App\Filament\Clusters\Research\Resources\ContactMessageResource\Pages;

use App\Filament\Clusters\Research\Resources\ContactMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;
}
