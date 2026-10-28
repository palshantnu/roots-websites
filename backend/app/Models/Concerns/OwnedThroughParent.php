<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * For child records (payments): visible only when their parent record is.
 * The model names its parent relation in ownerRelation().
 */
trait OwnedThroughParent
{
    abstract public static function ownerRelation(): string;

    public static function bootOwnedThroughParent(): void
    {
        static::addGlobalScope('owner', function (Builder $query) {
            if (User::restrictedToOwnRecords()) {
                $query->whereHas(static::ownerRelation());
            }
        });
    }
}
