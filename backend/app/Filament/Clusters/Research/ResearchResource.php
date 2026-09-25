<?php

namespace App\Filament\Clusters\Research;

use App\Enums\SiteCode;
use App\Filament\Clusters\Research;
use App\Filament\SiteContentResource;

/**
 * Base class for every resource in the Research admin area.
 */
abstract class ResearchResource extends SiteContentResource
{
    protected static ?string $cluster = Research::class;

    public static function getSiteCode(): SiteCode
    {
        return SiteCode::Research;
    }
}
