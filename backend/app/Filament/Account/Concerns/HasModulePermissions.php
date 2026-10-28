<?php

namespace App\Filament\Account\Concerns;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

/**
 * Gates a Resource's view/add/edit/delete on the logged-in user's role.
 * The resource sets `protected static string $permissionModule` to a key of
 * App\Support\AccountPermissions::MODULES.
 */
trait HasModulePermissions
{
    public static function userCan(string $action): bool
    {
        return (bool) auth()->user()?->hasAccountPermission(static::$permissionModule, $action);
    }

    /** "Added by" column — only for admins and managers, who see everyone's records. */
    public static function addedByColumn(string $label = 'Added by'): TextColumn
    {
        return TextColumn::make('creator.name')
            ->label($label)
            ->placeholder('—')
            ->visible(fn () => (bool) auth()->user()?->seesAllRecords())
            ->toggleable();
    }

    public static function canViewAny(): bool
    {
        return static::userCan('view');
    }

    public static function canView(Model $record): bool
    {
        return static::userCan('view');
    }

    public static function canCreate(): bool
    {
        return static::userCan('create');
    }

    public static function canEdit(Model $record): bool
    {
        return static::userCan('edit');
    }

    public static function canDelete(Model $record): bool
    {
        return static::userCan('delete');
    }

    public static function canDeleteAny(): bool
    {
        return static::userCan('delete');
    }
}
