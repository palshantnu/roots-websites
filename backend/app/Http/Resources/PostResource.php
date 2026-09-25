<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'category' => $this->category,
            'author' => $this->author,
            'color' => $this->color,
            'image' => $this->imageUrl(),
            'date' => $this->published_at?->format('d M Y'),
            'publishedAt' => $this->published_at?->toDateString(),
            'time' => $this->read_time,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
        ];
    }
}
