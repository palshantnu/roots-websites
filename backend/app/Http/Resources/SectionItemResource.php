<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'icon' => $this->icon,
            'tone' => $this->tone,
            'link' => $this->link,
            'value' => $this->value,
            'suffix' => $this->suffix,
            'meta' => $this->meta,
            'image' => $this->imageUrl(),
        ];
    }
}
