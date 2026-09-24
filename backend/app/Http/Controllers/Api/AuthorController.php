<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return AuthorResource::collection(
            Author::withCount('books')->orderBy('name')->get()
        );
    }

    public function show(string $slug): AuthorResource
    {
        return new AuthorResource(
            Author::withCount('books')->where('slug', $slug)->firstOrFail()
        );
    }
}
