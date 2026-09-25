<?php

namespace App\Models;

use App\Enums\SiteCode;
use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use BelongsToSite;

    protected $fillable = [
        'site_name', 'tagline', 'announcement_text', 'hero_eyebrow', 'hero_title', 'hero_copy',
        'stat_community_members', 'stat_registered_writers', 'stat_books_published', 'stat_countries_reached',
        'testimonial_quote', 'testimonial_author',
        'contact_email', 'contact_phone', 'contact_address', 'contact_hours',
        'instagram_url', 'facebook_url', 'linkedin_url', 'twitter_url', 'footer_note',
        'footer_text', 'founded_year', 'map_query',
    ];

    protected function casts(): array
    {
        return [
            'founded_year' => 'integer',
        ];
    }

    /**
     * The settings row of a site (one row per site). Defaults to Publications,
     * which is what every caller used before settings became site-aware.
     */
    public static function current(Site|SiteCode $site = SiteCode::Publications): self
    {
        $site = $site instanceof Site ? $site : Site::findByCode($site);

        $setting = static::query()->forSite($site)->first();

        if ($setting === null) {
            $setting = (new static)->site()->associate($site);
            $setting->save();
            $setting->refresh();
        }

        return $setting;
    }
}
