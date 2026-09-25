<?php

namespace App\Filament\Clusters\It\Resources\PostResource\Pages;

use App\Filament\Clusters\It\Resources\PostResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = PostResource::class;
}
