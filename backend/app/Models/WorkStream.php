<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A single piece of work/project the company is doing for a client, scoped to
 * one service (IT, Research, …). Tracks its cost, the advance received and the
 * remaining (pending) payments.
 */
class WorkStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_service_id',
        'title',
        'cost',
        'advance_payment',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'advance_payment' => 'decimal:2',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AccountService::class, 'account_service_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(WorkStreamPayment::class);
    }

    /** Total money received so far: the advance plus every payment marked paid. */
    protected function received(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->advance_payment
                + (float) $this->payments->where('is_paid', true)->sum('amount'),
        );
    }

    /** Money still owed by the client: cost minus what has been received. */
    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->cost - $this->received,
        );
    }
}
