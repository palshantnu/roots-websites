<?php

namespace App\Models;

use App\Enums\SampleType;
use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\ResolvesMediaUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * A downloadable sample document (research paper, thesis or synopsis).
 *
 * Documents live on the private `local` disk and are only reachable through
 * the site-scoped API, which checks that the sample is published. Thumbnails
 * are public images, like book covers.
 */
class Sample extends Model
{
    use BelongsToSite, HasFactory, Publishable, ResolvesMediaUrl;

    public const DOCUMENT_DISK = 'local';

    public const THUMBNAIL_DISK = 'public';

    /**
     * @var array<string, string>
     */
    public const DOCUMENT_MIME_TYPES = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /**
     * @var list<string>
     */
    public const THUMBNAIL_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    public const MAX_DOCUMENT_KILOBYTES = 20480;

    public const MAX_THUMBNAIL_KILOBYTES = 2048;

    protected $fillable = [
        'type', 'title', 'slug', 'short_description', 'description',
        'file_path', 'file_name', 'thumbnail', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => SampleType::class,
            'is_published' => 'boolean',
            'file_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Sample $sample) {
            if ($sample->isDirty('file_path')) {
                $disk = Storage::disk(self::DOCUMENT_DISK);
                $hasFile = $sample->file_path && $disk->exists($sample->file_path);

                $sample->file_size = $hasFile ? $disk->size($sample->file_path) : null;
                $sample->file_mime = $hasFile ? $disk->mimeType($sample->file_path) : null;
            }
        });

        static::updated(function (Sample $sample) {
            if ($sample->wasChanged('file_path')) {
                $sample->deleteStoredFile(self::DOCUMENT_DISK, $sample->getOriginal('file_path'));
            }

            if ($sample->wasChanged('thumbnail')) {
                $sample->deleteStoredFile(self::THUMBNAIL_DISK, $sample->getOriginal('thumbnail'));
            }
        });

        static::deleted(function (Sample $sample) {
            $sample->deleteStoredFile(self::DOCUMENT_DISK, $sample->file_path);
            $sample->deleteStoredFile(self::THUMBNAIL_DISK, $sample->thumbnail);
        });
    }

    public function scopeOfType(Builder $query, SampleType $type): void
    {
        $query->where($query->qualifyColumn('type'), $type->value);
    }

    public function thumbnailUrl(): ?string
    {
        return $this->resolveMediaUrl($this->thumbnail);
    }

    public function fileExtension(): ?string
    {
        return $this->file_path ? strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)) : null;
    }

    public function hasDocument(): bool
    {
        return $this->file_path !== null && Storage::disk(self::DOCUMENT_DISK)->exists($this->file_path);
    }

    /**
     * A safe download name built from the slug, never from the uploaded name.
     */
    public function downloadName(): string
    {
        return $this->slug.'.'.$this->fileExtension();
    }

    private function deleteStoredFile(string $disk, ?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk($disk)->delete($path);
        }
    }
}
