<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SampleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hasDocument = $this->file_path !== null;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'type' => $this->type->value,
            'typeLabel' => $this->type->getLabel(),
            'title' => $this->title,
            'shortDescription' => $this->short_description,
            'description' => $this->description,
            'thumbnail' => $this->thumbnailUrl(),
            'file' => $hasDocument ? [
                'extension' => $this->fileExtension(),
                'size' => $this->file_size,
                'viewUrl' => route($this->site->code.'.samples.file', $this->slug),
                'downloadUrl' => route($this->site->code.'.samples.download', $this->slug),
            ] : null,
        ];
    }
}
