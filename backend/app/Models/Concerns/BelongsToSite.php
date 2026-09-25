<?php

namespace App\Models\Concerns;

use App\Enums\SiteCode;
use App\Models\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Content that belongs to exactly one website. Every public API query and
 * every admin resource query goes through `forSite()`, which is what keeps
 * Publications, Research and IT content separated.
 */
trait BelongsToSite
{
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeForSite(Builder $query, Site|SiteCode|string $site): void
    {
        $site = $site instanceof Site ? $site : Site::findByCode($site);

        $query->where($query->qualifyColumn('site_id'), $site->getKey());
    }
}
