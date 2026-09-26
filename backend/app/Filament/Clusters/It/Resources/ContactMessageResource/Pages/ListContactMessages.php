<?php

namespace App\Filament\Clusters\It\Resources\ContactMessageResource\Pages;

use App\Filament\Clusters\It\Resources\ContactMessageResource;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;
}
