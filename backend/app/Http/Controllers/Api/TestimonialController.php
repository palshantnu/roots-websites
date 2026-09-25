<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TestimonialController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return TestimonialResource::collection(
            Testimonial::forSite($this->site($request))->published()->orderBy('sort_order')->get()
        );
    }
}
