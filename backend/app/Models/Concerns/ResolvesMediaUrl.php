<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesMediaUrl
{
    protected function resolveMediaUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return str_starts_with($value, 'http')
            ? $value
            : Storage::disk('public')->url($value);
    }
}
