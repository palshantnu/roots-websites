<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SitePageResource;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SitePageController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return SitePageResource::collection(
            SitePage::forSite($this->site($request))->published()->orderBy('slug')->get()
        );
    }

    public function show(Request $request, string $slug): SitePageResource
    {
        return new SitePageResource(
            SitePage::forSite($this->site($request))->published()->where('slug', $slug)->firstOrFail()
        );
    }
}
