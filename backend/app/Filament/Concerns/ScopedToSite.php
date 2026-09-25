<?php

namespace App\Filament\Concerns;

use App\Enums\SiteCode;
use App\Models\Site;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

/**
 * Locks a Filament resource to one website.
 *
 * Filament uses getEloquentQuery() for the table, for global search and for
 * resolving the record on edit pages, so records of another site can neither
 * be listed nor opened through this resource.
 */
trait ScopedToSite
{
    abstract public static function getSiteCode(): SiteCode;

    public static function getSite(): Site
    {
        return Site::findByCode(static::getSiteCode());
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->forSite(static::getSite());
    }

    /**
     * Scopes a `unique` validation rule (e.g. slugs) to this resource's site.
     */
    public static function uniqueWithinSite(): Closure
    {
        return fn (Unique $rule): Unique => $rule->where('site_id', static::getSite()->getKey());
    }
}
