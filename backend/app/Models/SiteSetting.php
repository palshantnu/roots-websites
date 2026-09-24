<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name', 'tagline', 'announcement_text', 'hero_eyebrow', 'hero_title', 'hero_copy',
        'stat_community_members', 'stat_registered_writers', 'stat_books_published', 'stat_countries_reached',
        'testimonial_quote', 'testimonial_author',
        'contact_email', 'contact_phone', 'contact_address', 'contact_hours',
        'instagram_url', 'facebook_url', 'linkedin_url',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
