<?php

namespace App\Models;

use App\Models\Concerns\OwnedByCreator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * A price proposal sent to a client from one of the companies. Once the client
 * accepts it, it is finalised into a project with a final and advance amount.
 */
class Proposal extends Model
{
    use HasFactory;
    use OwnedByCreator;

    public const STATUS_PROPOSAL = 'proposal';

    public const STATUS_FINAL = 'final';

    protected $fillable = [
        'proposal_company_id',
        'account_service_id',
        'proposal_no',
        'proposal_date',
        'client_name',
        'client_organization',
        'client_email',
        'client_phone',
        'subject',
        'greeting',
        'introduction',
        'package_includes',
        'items',
        'total_amount',
        'payment_terms',
        'deliverables',
        'status',
        'final_amount',
        'advance_amount',
        'finalized_at',
        'created_by',
    ];

    protected $attributes = [
        'status' => self::STATUS_PROPOSAL,
    ];

    protected function casts(): array
    {
        return [
            'proposal_date' => 'date',
            'items' => 'array',
            'total_amount' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'advance_amount' => 'decimal:2',
            'finalized_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Proposal $proposal) {
            $proposal->proposal_no ??= static::nextNumber($proposal->proposal_company_id);
        });

        static::saving(function (Proposal $proposal) {
            $proposal->total_amount = $proposal->total;
        });
    }

    /** e.g. RT/2026/0007 — running number per company per year. */
    public static function nextNumber(int $companyId): string
    {
        $code = ProposalCompany::whereKey($companyId)->value('code') ?? 'PR';
        $prefix = $code.'/'.now()->year.'/';

        // Across everyone's proposals, so numbers never repeat.
        $last = static::withoutGlobalScope('owner')
            ->where('proposal_no', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('proposal_no');

        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(ProposalCompany::class, 'proposal_company_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AccountService::class, 'account_service_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ProposalPayment::class)->latest('paid_on')->latest('id');
    }

    public function isFinal(): bool
    {
        return $this->status === self::STATUS_FINAL;
    }

    /** Sum of every payment stage. */
    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn () => round((float) collect($this->items ?? [])->sum(fn ($item) => (float) ($item['amount'] ?? 0)), 2),
        );
    }

    /** Indian digit grouping without a currency sign, e.g. 1,25,000 or 5,700.50. */
    public static function formatAmount(float|string|null $value): string
    {
        $value = round((float) $value, 2);
        [$whole, $fraction] = explode('.', number_format(abs($value), 2, '.', ''));

        if (strlen($whole) > 3) {
            $head = substr($whole, 0, -3);
            $whole = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $head).','.substr($whole, -3);
        }

        return ($value < 0 ? '-' : '').$whole.($fraction === '00' ? '' : '.'.$fraction);
    }

    /** Advance plus every payment recorded after it. Uses withSum() when the query loaded it. */
    protected function received(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) $this->advance_amount + (float) (array_key_exists('payments_sum_amount', $this->attributes)
                ? $this->attributes['payments_sum_amount']
                : $this->payments()->sum('amount')),
        );
    }

    /**
     * Every amount received, oldest first: the advance (dated when the project
     * was finalised) followed by each payment. Keys are 'advance' or payment ids.
     *
     * @return Collection<int, array{key: string|int, no: int, date: ?Carbon, stage: ?string, mode: ?string, note: ?string, amount: float}>
     */
    public function receiptLines(): Collection
    {
        $lines = collect();

        if ((float) $this->advance_amount > 0) {
            $lines->push([
                'key' => 'advance',
                'date' => $this->finalized_at,
                'stage' => 'Advance',
                'mode' => null,
                'note' => null,
                'amount' => (float) $this->advance_amount,
            ]);
        }

        $this->payments()->reorder()->oldest('paid_on')->oldest('id')->get()
            ->each(fn (ProposalPayment $payment) => $lines->push([
                'key' => $payment->id,
                'date' => $payment->paid_on,
                'stage' => $payment->stage,
                'mode' => $payment->mode,
                'note' => $payment->note,
                'amount' => (float) $payment->amount,
            ]));

        return $lines->values()->map(fn (array $line, int $i) => ['no' => $i + 1] + $line);
    }

    /** What the client still owes on a final project. */
    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn () => round((float) $this->final_amount - $this->received, 2),
        );
    }
}
