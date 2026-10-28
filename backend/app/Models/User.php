<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_admin' => false,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /** Admins, and roles marked "sees all records", see everyone's records. */
    public function seesAllRecords(): bool
    {
        return $this->is_admin || (bool) $this->role?->sees_all_records;
    }

    /** The logged-in user when they may see only their own records, otherwise null. */
    public static function restrictedToOwnRecords(): ?self
    {
        $user = auth()->user();

        return $user instanceof self && ! $user->seesAllRecords() ? $user : null;
    }

    /** Proposals this user created. */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'created_by');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Only administrators may use the admin panel that manages all websites.
     * The Accounts panel is also open to staff who have been given a role.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'account') {
            return $this->is_admin || $this->role_id !== null;
        }

        return $this->is_admin;
    }

    /** Administrators have full access; everyone else gets what their role allows. */
    public function hasAccountPermission(string $module, string $action): bool
    {
        return $this->is_admin || (bool) $this->role?->allows($module, $action);
    }
}
