<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Per-page hero copy, call-to-action and SEO metadata for a website page.
 */
class SitePage extends Model
{
    use BelongsToSite, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = [
        'slug', 'name', 'eyebrow', 'title', 'highlight', 'description',
        'cta_title', 'cta_description', 'cta_label',
        'meta_title', 'meta_description', 'og_image', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function ogImageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->og_image);
    }
}
