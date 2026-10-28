<?php

namespace App\Support;

use Carbon\CarbonImmutable;

/** The period choices shared by every Accounts report, and their date range. */
class ReportPeriod
{
    public const OPTIONS = [
        'this_week' => 'This week',
        'last_week' => 'Last week',
        'this_month' => 'This month',
        'last_month' => 'Last month',
        'this_year' => 'This year',
        'all_time' => 'All time',
        'custom' => 'Custom dates',
    ];

    public static function valid(?string $period, string $default = 'this_month'): string
    {
        return array_key_exists((string) $period, self::OPTIONS) ? $period : $default;
    }

    /** @return array{CarbonImmutable, CarbonImmutable} */
    public static function range(string $period, ?string $from = null, ?string $to = null): array
    {
        $now = CarbonImmutable::now();

        $range = match ($period) {
            'this_week' => [$now->startOfWeek(), $now->endOfWeek()],
            'last_week' => [$now->subWeek()->startOfWeek(), $now->subWeek()->endOfWeek()],
            'last_month' => [$now->subMonthNoOverflow()->startOfMonth(), $now->subMonthNoOverflow()->endOfMonth()],
            'this_year' => [$now->startOfYear(), $now->endOfYear()],
            'all_time' => [CarbonImmutable::create(2000), $now->endOfDay()],
            'custom' => [
                $from ? CarbonImmutable::parse($from)->startOfDay() : $now->startOfMonth(),
                $to ? CarbonImmutable::parse($to)->endOfDay() : $now->endOfDay(),
            ],
            default => [$now->startOfMonth(), $now->endOfMonth()],
        };

        return $range[0]->gt($range[1]) ? [$range[1]->startOfDay(), $range[0]->endOfDay()] : $range;
    }

    /** "01 Oct 2026 to 31 Oct 2026", or "All time (till 28 Oct 2026)". */
    public static function label(string $period, CarbonImmutable $from, CarbonImmutable $to): string
    {
        return $period === 'all_time'
            ? 'All time (till '.$to->format('d M Y').')'
            : $from->format('d M Y').' to '.$to->format('d M Y');
    }
}
