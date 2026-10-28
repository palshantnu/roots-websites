<?php

namespace App\Models;

use App\Models\Concerns\OwnedByCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Money spent by the company (the "out" side). Optionally tagged to a service
 * so profit can be worked out per line of business later.
 */
class Expense extends Model
{
    use HasFactory;
    use OwnedByCreator;

    /** Expense categories shown in the admin dropdown. */
    public const CATEGORIES = [
        'Salary' => 'Salary',
        'Office & Rent' => 'Office & Rent',
        'Software & Tools' => 'Software & Tools',
        'Marketing & Ads' => 'Marketing & Ads',
        'Equipment' => 'Equipment',
        'Travel' => 'Travel',
        'Utilities' => 'Utilities',
        'Taxes & Fees' => 'Taxes & Fees',
        'Other' => 'Other',
    ];

    protected $fillable = [
        'account_service_id',
        'title',
        'category',
        'amount',
        'expense_date',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AccountService::class, 'account_service_id');
    }
}
