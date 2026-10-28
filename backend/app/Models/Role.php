<?php

namespace App\Models;

use App\Support\AccountPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A named set of permissions for the Accounts panel. Permissions are stored as
 * { "<module>": ["view", "create", "edit", "delete"] }.
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'permissions', 'sees_all_records'];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'sees_all_records' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function allows(string $module, string $action): bool
    {
        return in_array($action, $this->permissions[$module] ?? [], true);
    }

    /** Total permissions ticked, out of every module × action. */
    public function permissionCount(): int
    {
        return collect($this->permissions ?? [])
            ->only(array_keys(AccountPermissions::MODULES))
            ->sum(fn ($actions) => count((array) $actions));
    }
}
