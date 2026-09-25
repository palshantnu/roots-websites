<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\DeletesReplacedMedia;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One entry of a repeatable website section (stats, process steps, offerings,
 * trust badges, ...). The `section` key groups entries; the keys available to
 * each site are listed in config/sites.php.
 */
class SectionItem extends Model
{
    use BelongsToSite, DeletesReplacedMedia, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = [
        'section', 'title', 'subtitle', 'description', 'icon', 'tone', 'link',
        'value', 'suffix', 'meta', 'image', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
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
