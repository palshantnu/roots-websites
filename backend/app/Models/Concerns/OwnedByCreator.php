<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Records remember who created them (created_by). A logged-in user who may
 * not see everyone's records (not an admin, and their role has no "see all")
 * only ever gets their own records back — in lists, edit pages, reports,
 * downloads and dashboard figures alike.
 */
trait OwnedByCreator
{
    public static function bootOwnedByCreator(): void
    {
        static::addGlobalScope('owner', function (Builder $query) {
            if ($user = User::restrictedToOwnRecords()) {
                $query->where($query->qualifyColumn('created_by'), $user->id);
            }
        });

        static::creating(function ($model) {
            $model->created_by ??= auth()->id();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
