<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\DeletesReplacedMedia;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Per-page hero copy, call-to-action and SEO metadata for a website page.
 * `content` holds optional structured blocks for richer pages (the IT
 * service detail pages: offerings, process, benefits, FAQs, ...).
 */
class SitePage extends Model
{
    use BelongsToSite, DeletesReplacedMedia, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = [
        'slug', 'name', 'eyebrow', 'title', 'highlight', 'description',
        'cta_title', 'cta_description', 'cta_label', 'cta_secondary_label', 'content',
        'meta_title', 'meta_description', 'og_image', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected function mediaAttributes(): array
    {
        return ['og_image' => 'public'];
    }

    public function ogImageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->og_image);
    }
}
