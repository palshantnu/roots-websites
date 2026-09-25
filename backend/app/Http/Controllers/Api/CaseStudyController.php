<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseStudyResource;
use App\Models\CaseStudy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CaseStudyController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return CaseStudyResource::collection(
            CaseStudy::forSite($this->site($request))->published()->orderBy('sort_order')->orderBy('id')->get()
        );
    }

    public function show(Request $request, string $slug): CaseStudyResource
    {
        return new CaseStudyResource(
            CaseStudy::forSite($this->site($request))->published()->where('slug', $slug)->firstOrFail()
        );
    }
}
