<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Concerns\CreatesSiteRecords;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = PostResource::class;
}
