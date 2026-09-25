<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\DeletesReplacedMedia;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use BelongsToSite, DeletesReplacedMedia, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = ['name', 'role', 'company', 'avatar', 'quote', 'rating', 'sort_order', 'is_published'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    protected function mediaAttributes(): array
    {
        return ['avatar' => 'public'];
    }

    public function avatarUrl(): ?string
    {
        return $this->resolveMediaUrl($this->avatar);
    }
}
