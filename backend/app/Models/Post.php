<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'category', 'color', 'read_time', 'excerpt', 'body', 'published_at'];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }
}
