<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return ServiceResource::collection(
            Service::forSite($this->site($request))->published()->orderBy('sort_order')->get()
        );
    }

    public function show(Request $request, string $slug): ServiceResource
    {
        return new ServiceResource(
            Service::forSite($this->site($request))->published()->where('slug', $slug)->firstOrFail()
        );
    }
}
