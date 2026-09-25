<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    /**
     * Pass `?featured=1` for featured projects only.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            Project::forSite($this->site($request))
                ->published()
                ->when($request->boolean('featured'), fn ($query) => $query->where('is_featured', true))
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
        );
    }

    public function show(Request $request, string $slug): ProjectResource
    {
        return new ProjectResource(
            Project::forSite($this->site($request))->published()->where('slug', $slug)->firstOrFail()
        );
    }
}
