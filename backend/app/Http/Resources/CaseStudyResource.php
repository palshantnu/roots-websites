<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseStudyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'client' => $this->client,
            'industry' => $this->industry,
            'image' => $this->imageUrl(),
            'challenge' => $this->challenge,
            'solution' => $this->solution,
            'technologies' => $this->technologies ?? [],
            'results' => $this->results ?? [],
        ];
    }
}
