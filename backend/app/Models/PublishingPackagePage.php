<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishingPackagePage extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'eyebrow', 'title', 'heading', 'copy', 'benefits'];

    protected function casts(): array
    {
        return [
            'benefits' => 'array',
        ];
    }
}
