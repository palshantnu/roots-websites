<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A company a proposal can be issued from (Roots Technology, RTS Publication,
 * ThesisCraft Academy, …). Its details form the letterhead of the PDF.
 */
class ProposalCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'tagline',
        'logo',
        'address',
        'email',
        'phone',
        'website',
        'gstin',
        'footer_note',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
