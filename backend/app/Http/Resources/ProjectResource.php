<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'category' => $this->category,
            'industry' => $this->industry,
            'technologies' => $this->technologies ?? [],
            'description' => $this->description,
            'image' => $this->imageUrl(),
            'year' => $this->year,
            'featured' => $this->is_featured,
        ];
    }
}
