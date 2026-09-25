<?php

namespace App\Filament\Clusters\Research\Resources\PostResource\Pages;

use App\Filament\Clusters\Research\Resources\PostResource;
use App\Filament\Concerns\CreatesSiteRecords;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use CreatesSiteRecords;

    protected static string $resource = PostResource::class;
}
