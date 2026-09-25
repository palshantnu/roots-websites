<?php

namespace App\Filament;

use App\Filament\Concerns\ScopedToSite;
use Filament\Forms;
use Filament\Resources\Resource;

/**
 * Base for resources that manage one website's content (Research, IT, ...).
 * Form options come from config/sites.php under the resource's site code.
 */
abstract class SiteContentResource extends Resource
{
    use ScopedToSite;

    protected static function siteConfig(string $key, mixed $default = []): mixed
    {
        return config('sites.'.static::getSiteCode()->value.'.'.$key, $default);
    }

    /**
     * @return array<string, string>
     */
    protected static function iconOptions(string $key = 'icons'): array
    {
        $icons = static::siteConfig($key);

        return array_combine($icons, $icons);
    }

    /**
     * @return array<string, string>
     */
    protected static function toneOptions(): array
    {
        return static::siteConfig('tones');
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

    /**
     * A public image upload, validated like the other image uploads.
     */
    protected static function imageUpload(string $name, string $directory): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make($name)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize(2048)
            ->helperText('JPG, PNG or WEBP, up to 2 MB.');
    }
}
