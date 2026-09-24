<?php

namespace App\Models;

use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory, ResolvesMediaUrl;

    protected $fillable = ['name', 'slug', 'role', 'genre', 'bio', 'image'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function imageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }
}
