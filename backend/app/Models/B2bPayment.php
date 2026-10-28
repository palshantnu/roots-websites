<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Money received from a B2B client against one of its projects. */
class B2bPayment extends Model
{
    use HasFactory;

    protected $fillable = ['b2b_project_id', 'amount', 'paid_on', 'mode', 'reference', 'note'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_on' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(B2bProject::class, 'b2b_project_id');
    }
}
