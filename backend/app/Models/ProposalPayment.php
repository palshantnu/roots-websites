<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Money received from the client on a final project, after the advance.
 */
class ProposalPayment extends Model
{
    use HasFactory;

    public const MODES = [
        'Cash' => 'Cash',
        'UPI' => 'UPI',
        'Bank transfer' => 'Bank transfer',
        'Cheque' => 'Cheque',
        'Other' => 'Other',
    ];

    protected $fillable = ['proposal_id', 'amount', 'paid_on', 'stage', 'mode', 'note'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_on' => 'date',
        ];
    }

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
