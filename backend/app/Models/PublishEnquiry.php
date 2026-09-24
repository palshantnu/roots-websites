<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'email', 'phone', 'location', 'book_title', 'genre',
        'language', 'word_count', 'formats', 'manuscript_path', 'status',
    ];

    protected function casts(): array
    {
        return [
            'formats' => 'array',
        ];
    }
}
