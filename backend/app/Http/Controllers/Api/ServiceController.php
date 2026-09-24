<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ServiceResource::collection(
            Service::orderBy('sort_order')->get()
        );
    }

    public function show(string $slug): ServiceResource
    {
        return new ServiceResource(
            Service::where('slug', $slug)->firstOrFail()
        );
    }
}
