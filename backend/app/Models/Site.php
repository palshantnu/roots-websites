<?php

namespace App\Models;

use App\Enums\SiteCode;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['name', 'code', 'domain', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Resolve a site by its code. Fails loudly when the site row is missing so
     * content can never be silently written to or read from the wrong site.
     */
    public static function findByCode(SiteCode|string $code): self
    {
        $code = $code instanceof SiteCode ? $code->value : $code;

        return static::query()->where('code', $code)->firstOrFail();
    }
}
