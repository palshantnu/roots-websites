<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublishingPackagePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'eyebrow' => $this->eyebrow,
            'title' => $this->title,
            'heading' => $this->heading,
            'copy' => $this->copy,
            'benefits' => $this->benefits ?? [],
        ];
    }
}
