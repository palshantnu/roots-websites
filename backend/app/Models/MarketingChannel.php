<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Where marketing money goes: an ad platform (Google, Meta…), a person
 * (freelancer, influencer), an agency or offline. Dynamic — add more anytime.
 */
class MarketingChannel extends Model
{
    use HasFactory;

    public const TYPES = [
        'platform' => 'Ad platform',
        'person' => 'Person (freelancer / influencer)',
        'agency' => 'Agency',
        'offline' => 'Offline',
        'other' => 'Other',
    ];

    protected $fillable = ['name', 'type', 'is_active', 'sort_order'];

    protected $attributes = [
        'type' => 'platform',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(MarketingExpense::class);
    }
}
