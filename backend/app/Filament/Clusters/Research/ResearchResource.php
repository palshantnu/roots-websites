<?php

namespace App\Filament\Clusters\Research;

use App\Enums\SiteCode;
use App\Filament\Clusters\Research;
use App\Filament\Concerns\ScopedToSite;
use Filament\Forms;
use Filament\Resources\Resource;

/**
 * Base class for every resource in the Research admin area.
 */
abstract class ResearchResource extends Resource
{
    use ScopedToSite;

    protected static ?string $cluster = Research::class;

    public static function getSiteCode(): SiteCode
    {
        return SiteCode::Research;
    }

    /**
     * @return array<string, string>
     */
    protected static function iconOptions(): array
    {
        $icons = config('sites.research.icons', []);

        return array_combine($icons, $icons);
    }

    /**
     * @return array<string, string>
     */
    protected static function toneOptions(): array
    {
        return config('sites.research.tones', []);
    }

    protected static function publishedToggle(): Forms\Components\Toggle
    {
        return Forms\Components\Toggle::make('is_published')
            ->label('Published')
            ->helperText('Only published items are shown on the website.')
            ->default(true);
    }

    protected static function sortOrderInput(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('sort_order')
            ->label('Display order')
            ->numeric()
            ->minValue(0)
            ->default(0);
    }
}
