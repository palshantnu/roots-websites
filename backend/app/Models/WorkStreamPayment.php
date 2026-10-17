<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One pending/received instalment that belongs to a WorkStream.
 */
class WorkStreamPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_stream_id',
        'amount',
        'due_date',
        'note',
        'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'is_paid' => 'boolean',
        ];
    }

    public function workStream(): BelongsTo
    {
        return $this->belongsTo(WorkStream::class);
    }
}
