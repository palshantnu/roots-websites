<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\DeletesReplacedMedia;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use BelongsToSite, DeletesReplacedMedia, HasFactory, Publishable, ResolvesMediaUrl;

    protected $fillable = ['title', 'slug', 'category', 'author', 'color', 'image', 'read_time', 'excerpt', 'body', 'published_at', 'is_published'];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
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
