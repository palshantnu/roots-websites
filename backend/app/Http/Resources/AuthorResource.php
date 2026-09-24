<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'role' => $this->role,
            'genre' => $this->genre,
            'books' => $this->books_count ?? $this->books()->count(),
            'bio' => $this->bio,
            'image' => $this->imageUrl(),
        ];
    }
}
