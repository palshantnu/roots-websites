<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'siteName' => $this->site_name,
            'tagline' => $this->tagline,
            'announcementText' => $this->announcement_text,
            'footerNote' => $this->footer_note,
            'hero' => [
                'eyebrow' => $this->hero_eyebrow,
                'title' => $this->hero_title,
                'copy' => $this->hero_copy,
            ],
            'stats' => [
                'communityMembers' => $this->stat_community_members,
                'registeredWriters' => $this->stat_registered_writers,
                'booksPublished' => $this->stat_books_published,
                'countriesReached' => $this->stat_countries_reached,
            ],
            'testimonial' => [
                'quote' => $this->testimonial_quote,
                'author' => $this->testimonial_author,
            ],
            'contact' => [
                'email' => $this->contact_email,
                'phone' => $this->contact_phone,
                'address' => $this->contact_address,
                'hours' => $this->contact_hours,
            ],
            'socials' => [
                'instagram' => $this->instagram_url,
                'facebook' => $this->facebook_url,
                'linkedin' => $this->linkedin_url,
                'twitter' => $this->twitter_url,
            ],
        ];
    }
}
