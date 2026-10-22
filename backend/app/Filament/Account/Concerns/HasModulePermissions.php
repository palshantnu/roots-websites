<?php

namespace App\Filament\Account\Concerns;

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
