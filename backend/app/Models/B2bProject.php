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
 * A project done for a B2B client, with its value and the payments received.
 */
class B2bProject extends Model
{
    use HasFactory;
    use OwnedByCreator;

    public const STATUSES = [
        'pending' => 'Pending',
        'in_progress' => 'In progress',
        'on_hold' => 'On hold',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'b2b_client_id',
        'proposal_company_id',
        'account_service_id',
        'employee_id',
        'project_no',
        'title',
        'description',
        'start_date',
        'due_date',
        'completed_on',
        'value',
        'status',
        'notes',
    ];

    protected $attributes = [
        'status' => 'in_progress',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_on' => 'date',
            'value' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (B2bProject $project) {
            $project->project_no ??= static::nextNumber();
        });

        static::saving(function (B2bProject $project) {
            if ($project->status === 'completed') {
                $project->completed_on ??= now();
            }
        });
    }

    /** e.g. B2B/2026/0007 — running number per year. */
    public static function nextNumber(): string
    {
        $prefix = 'B2B/'.now()->year.'/';

        // Across everyone's projects, so numbers never repeat.
        $last = static::withoutGlobalScope('owner')
            ->where('project_no', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('project_no');

        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public static function statusColor(?string $status): string
    {
        return match ($status) {
            'completed' => 'success',
            'in_progress' => 'warning',
            'on_hold' => 'info',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(B2bClient::class, 'b2b_client_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(ProposalCompany::class, 'proposal_company_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(AccountService::class, 'account_service_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(B2bPayment::class)->latest('paid_on')->latest('id');
    }

    /** Sum of payments. Uses withSum() when the query loaded it. */
    protected function received(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) (array_key_exists('payments_sum_amount', $this->attributes)
                ? $this->attributes['payments_sum_amount']
                : $this->payments()->sum('amount')),
        );
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn () => round((float) $this->value - $this->received, 2),
        );
    }

    /**
     * Every payment, oldest first, numbered 1, 2, 3… for invoice numbers.
     *
     * @return Collection<int, array{key: int, no: int, date: ?Carbon, stage: ?string, mode: ?string, note: ?string, amount: float}>
     */
    public function receiptLines(): Collection
    {
        return $this->payments()->reorder()->oldest('paid_on')->oldest('id')->get()
            ->values()
            ->map(fn (B2bPayment $payment, int $i) => [
                'key' => $payment->id,
                'no' => $i + 1,
                'date' => $payment->paid_on,
                'stage' => $payment->reference ? 'Payment · Ref '.$payment->reference : null,
                'mode' => $payment->mode,
                'note' => $payment->note,
                'amount' => (float) $payment->amount,
            ]);
    }
}
