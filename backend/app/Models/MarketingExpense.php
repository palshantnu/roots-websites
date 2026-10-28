<?php

namespace App\Models;

use App\Models\Concerns\OwnedByCreator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Money spent to grow the business: an ad payment to a platform, or a payment
 * to a person / agency doing marketing. Counted in the dashboard's expenses.
 */
class MarketingExpense extends Model
{
    use HasFactory;
    use OwnedByCreator;

    protected $fillable = [
        'marketing_channel_id',
        'account_service_id',
        'proposal_company_id',
        'created_by',
        'title',
        'campaign',
        'paid_to',
        'amount',
        'spent_on',
        'runs_from',
        'runs_to',
        'mode',
        'reference',
        'leads',
        'receipt',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'spent_on' => 'date',
            'runs_from' => 'date',
            'runs_to' => 'date',
            'leads' => 'integer',
        ];
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(MarketingChannel::class, 'marketing_channel_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AccountService::class, 'account_service_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(ProposalCompany::class, 'proposal_company_id');
    }

    /** Cost per lead, when leads were recorded. */
    public static function costPerLead(float $amount, ?int $leads): ?float
    {
        return $leads ? round($amount / $leads, 2) : null;
    }
}
