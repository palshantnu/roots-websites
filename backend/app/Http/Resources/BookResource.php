<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'author' => $this->author->name,
            'authorSlug' => $this->author->slug,
            'category' => $this->category->name,
            'categorySlug' => $this->category->slug,
            'price' => $this->price,
            'originalPrice' => $this->original_price,
            'color' => $this->color,
            'coverImage' => $this->coverImageUrl(),
            'isbn' => $this->isbn,
            'pages' => $this->pages,
            'language' => $this->language,
            'format' => $this->format,
            'rating' => (float) $this->rating,
            'reviewCount' => $this->review_count,
            'featured' => (bool) $this->featured,
            'bestseller' => (bool) $this->bestseller,
            'newRelease' => (bool) $this->new_release,
            'description' => $this->description,
        ];
    }
}
