<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A company employee with a monthly salary. Work streams are assigned to
 * employees so it's clear who is handling which project.
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'phone',
        'email',
        'joining_date',
        'monthly_salary',
        'is_active',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'monthly_salary' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function workStreams(): HasMany
    {
        return $this->hasMany(WorkStream::class);
    }
}
