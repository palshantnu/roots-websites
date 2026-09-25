<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return PostResource::collection(
            Post::forSite($this->site($request))->published()->orderByDesc('published_at')->get()
        );
    }

    public function show(Request $request, string $slug): PostResource
    {
        return new PostResource(
            Post::forSite($this->site($request))->published()->where('slug', $slug)->firstOrFail()
        );
    }
}
