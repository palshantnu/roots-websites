<?php

namespace App\Models;

use App\Models\Concerns\OwnedThroughParent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Money received from the client on a final project, after the advance.
 */
class ProposalPayment extends Model
{
    use HasFactory;
    use OwnedThroughParent;

    public const MODES = [
        'Cash' => 'Cash',
        'UPI' => 'UPI',
        'Bank transfer' => 'Bank transfer',
        'Cheque' => 'Cheque',
        'Other' => 'Other',
    ];

    protected $fillable = ['proposal_id', 'amount', 'paid_on', 'stage', 'mode', 'note'];

    /** Visible only to whoever can see this parent record. */
    public static function ownerRelation(): string
    {
        return 'proposal';
    }

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
