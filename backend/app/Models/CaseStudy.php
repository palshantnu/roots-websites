<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\DeletesReplacedMedia;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A client case study: challenge, solution, technologies and results.
 */
class CaseStudy extends Model
{
    use BelongsToSite, DeletesReplacedMedia, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = [
        'title', 'slug', 'client', 'industry', 'image', 'challenge', 'solution',
        'technologies', 'results', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'results' => 'array',
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
