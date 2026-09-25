<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SitePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'eyebrow' => $this->eyebrow,
            'title' => $this->title,
            'highlight' => $this->highlight,
            'description' => $this->description,
            'cta' => [
                'title' => $this->cta_title,
                'description' => $this->cta_description,
                'label' => $this->cta_label,
            ],
            'seo' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'image' => $this->ogImageUrl(),
            ],
            'updatedAt' => $this->updated_at?->toIso8601String(),
        ];
    }
}
