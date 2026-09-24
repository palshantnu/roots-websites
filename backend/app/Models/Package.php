<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'price', 'tone', 'popular', 'features', 'sort_order'];

    protected function casts(): array
    {
        return [
            'popular' => 'boolean',
            'features' => 'array',
        ];
    }
}
