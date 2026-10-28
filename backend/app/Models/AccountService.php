<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A line of business the company works in (IT, Research, Publication,
 * Digital Marketing, …). Dynamic — more can be added from the admin.
 */
class AccountService extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function workStreams(): HasMany
    {
        return $this->hasMany(WorkStream::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function b2bProjects(): HasMany
    {
        return $this->hasMany(B2bProject::class);
    }
}
