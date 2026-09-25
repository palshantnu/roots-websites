<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use BelongsToSite, HasFactory, Publishable;

    protected $fillable = ['title', 'slug', 'category', 'author', 'color', 'read_time', 'excerpt', 'body', 'published_at', 'is_published'];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
        ];
    }
}
