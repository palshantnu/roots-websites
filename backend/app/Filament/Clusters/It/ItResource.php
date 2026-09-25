<?php

namespace App\Filament\Clusters\It;

use App\Enums\SiteCode;
use App\Filament\Clusters\It;
use App\Filament\SiteContentResource;

/**
 * Base class for every resource in the IT admin area.
 */
abstract class ItResource extends SiteContentResource
{
    protected static ?string $cluster = It::class;

    public static function getSiteCode(): SiteCode
    {
        return SiteCode::It;
    }
}
