<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use BelongsToSite, HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'company', 'city', 'subject', 'service', 'budget', 'message', 'read'];

    protected function casts(): array
    {
        return [
            'read' => 'boolean',
        ];
    }
}
