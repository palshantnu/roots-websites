<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Removes uploaded files from storage when they are replaced or when the
 * record is deleted. Models list their file attributes in mediaAttributes().
 * External URLs (e.g. seeded placeholder images) are never touched.
 */
trait DeletesReplacedMedia
{
    /**
     * @return array<string, string> attribute => disk
     */
    abstract protected function mediaAttributes(): array;

    public static function bootDeletesReplacedMedia(): void
    {
        static::updated(function (Model $model) {
            foreach ($model->mediaAttributes() as $attribute => $disk) {
                if ($model->wasChanged($attribute)) {
                    static::deleteStoredMedia($disk, $model->getOriginal($attribute));
                }
            }
        });

        static::deleted(function (Model $model) {
            foreach ($model->mediaAttributes() as $attribute => $disk) {
                static::deleteStoredMedia($disk, $model->getAttribute($attribute));
            }
        });
    }

    protected static function deleteStoredMedia(string $disk, ?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk($disk)->delete($path);
        }
    }
}
