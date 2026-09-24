<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): array
    {
        $search = $request->string('q')->trim()->value();

        $books = collect();

        if ($search) {
            $books = Book::with(['author', 'category'])
                ->where('title', 'like', "%{$search}%")
                ->orWhereHas('author', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('category', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                ->get();
        }

        return [
            'query' => $search,
            'books' => BookResource::collection($books),
        ];
    }
}
