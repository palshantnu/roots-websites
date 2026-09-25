<?php

namespace App\Enums;

/**
 * The websites served by this backend. Each value matches the `code` column
 * of the `sites` table and is the only way code refers to a site — numeric
 * site IDs are never hardcoded.
 */
enum SiteCode: string
{
    case Publications = 'publications';
    case Research = 'research';
    case It = 'it';

    public function label(): string
    {
        return match ($this) {
            self::Publications => 'Publications',
            self::Research => 'Research',
            self::It => 'IT',
        };
    }
}
