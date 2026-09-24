<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(
            Post::orderByDesc('published_at')->get()
        );
    }

    public function show(string $slug): PostResource
    {
        return new PostResource(
            Post::where('slug', $slug)->firstOrFail()
        );
    }
}
