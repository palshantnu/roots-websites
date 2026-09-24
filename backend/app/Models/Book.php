<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory, ResolvesMediaUrl;

    protected $fillable = [
        'title', 'slug', 'author_id', 'category_id', 'price', 'original_price',
        'color', 'cover_image', 'isbn', 'pages', 'language', 'format',
        'rating', 'review_count', 'featured', 'bestseller', 'new_release', 'description',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'bestseller' => 'boolean',
            'new_release' => 'boolean',
            'rating' => 'float',
            'price' => 'integer',
            'original_price' => 'integer',
        ];
    }

    public function coverImageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->cover_image);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
