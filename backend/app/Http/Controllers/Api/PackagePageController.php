<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublishingPackagePageResource;
use App\Models\PublishingPackagePage;

class PackagePageController extends Controller
{
    public function show(string $slug): PublishingPackagePageResource
    {
        return new PublishingPackagePageResource(
            PublishingPackagePage::where('slug', $slug)->firstOrFail()
        );
    }
}
