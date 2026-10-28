<?php

namespace App\Models;

use App\Models\Concerns\OwnedByCreator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * A business client (company, institute, agency…) that gives us projects.
 * Its overall business = value of all its projects.
 */
class B2bClient extends Model
{
    use HasFactory;
    use OwnedByCreator;

    protected $fillable = [
        'company_name',
        'contact_person',
        'designation',
        'email',
        'phone',
        'alt_phone',
        'website',
        'gstin',
        'pan',
        'address',
        'city',
        'state',
        'pincode',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(B2bProject::class);
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(B2bPayment::class, B2bProject::class);
    }

    /** Adds project count, business (sum of values) and received (sum of payments). */
    public function scopeWithTotals(Builder $query): Builder
    {
        return $query
            ->withCount('projects')
            ->withSum('projects', 'value')
            ->withSum('payments', 'amount');
    }

    /** Total value of every project. Uses withTotals() when the query loaded it. */
    protected function business(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) (array_key_exists('projects_sum_value', $this->attributes)
                ? $this->attributes['projects_sum_value']
                : $this->projects()->sum('value')),
        );
    }

    protected function received(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) (array_key_exists('payments_sum_amount', $this->attributes)
                ? $this->attributes['payments_sum_amount']
                : $this->payments()->sum('b2b_payments.amount')),
        );
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->business - $this->received, 2),
        );
    }

    /** Address on one line: street, city, state - pincode. */
    public function fullAddress(): ?string
    {
        $place = collect([$this->city, $this->state])->filter()->implode(', ');

        return collect([$this->address, $place.($this->pincode ? ' - '.$this->pincode : '')])
            ->filter(fn ($part) => filled(trim((string) $part, ' -')))
            ->implode(', ') ?: null;
    }
}
