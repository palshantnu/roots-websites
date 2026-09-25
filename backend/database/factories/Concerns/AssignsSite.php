<?php

namespace Database\Factories\Concerns;

use App\Enums\SiteCode;
use App\Models\Site;
use Closure;

trait AssignsSite
{
    /**
     * Attribute resolver for `site_id`; defaults to the given site.
     */
    protected function siteId(SiteCode $default): Closure
    {
        return fn (): int => Site::findByCode($default)->getKey();
    }

    public function forSite(SiteCode $site): static
    {
        return $this->state(fn (array $attributes) => [
            'site_id' => Site::findByCode($site)->getKey(),
        ]);
    }
}
