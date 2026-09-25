<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\DeletesReplacedMedia;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A portfolio project shown on a website's portfolio page.
 */
class Project extends Model
{
    use BelongsToSite, DeletesReplacedMedia, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = [
        'name', 'slug', 'category', 'industry', 'technologies', 'description',
        'image', 'year', 'is_featured', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'year' => 'integer',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    protected function mediaAttributes(): array
    {
        return ['image' => 'public'];
    }

    public function imageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }
}
