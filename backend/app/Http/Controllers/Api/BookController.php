<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Book::query()->with(['author', 'category']);

        if ($search = $request->string('q')->trim()->value()) {
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhereHas('author', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        if ($category = $request->string('category')->trim()->value()) {
            if (! in_array(strtolower($category), ['all categories', 'all'], true)) {
                $categorySlug = Str::slug($category);
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            }
        }

        match ($request->string('sort')->value()) {
            'new' => $query->orderByDesc('new_release')->orderByDesc('created_at'),
            'bestseller' => $query->orderByDesc('bestseller'),
            'low' => $query->orderBy('price'),
            'high' => $query->orderByDesc('price'),
            default => $query->orderByDesc('featured')->orderByDesc('created_at'),
        };

        return BookResource::collection($query->get());
    }

    public function show(string $slug): BookResource
    {
        return new BookResource(
            Book::with(['author', 'category'])->where('slug', $slug)->firstOrFail()
        );
    }
}
