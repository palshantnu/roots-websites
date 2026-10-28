<?php

namespace App\Support;

use App\Models\B2bPayment;
use App\Models\B2bProject;
use App\Models\Expense;
use App\Models\MarketingExpense;
use App\Models\Proposal;
use App\Models\ProposalPayment;
use App\Models\WorkStream;
use App\Models\WorkStreamPayment;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Money figures for the Accounts dashboard. Totals are grouped in PHP so the
 * same code works on MySQL (live) and SQLite (tests).
 *
 * "Received" = work stream advances + paid instalments
 *            + final project advances + payments recorded on them
 *            + payments received on B2B projects.
 */
class AccountMetrics
{
    /** Month starts, oldest first, ending with the current month. */
    public static function months(int $count): Collection
    {
        $current = CarbonImmutable::now()->startOfMonth();

        return collect(range($count - 1, 0))->map(fn (int $back) => $current->subMonths($back));
    }

    /** @return array<string, float> received per month, keyed Y-m */
    public static function receivedByMonth(int $count): array
    {
        [$from, $to, $buckets] = static::range($count);

        $entries = collect()
            ->concat(WorkStream::whereBetween('created_at', [$from, $to])
                ->where('advance_payment', '>', 0)
                ->get(['advance_payment', 'created_at'])
                ->map(fn ($w) => [$w->created_at, $w->advance_payment]))
            ->concat(WorkStreamPayment::where('is_paid', true)
                ->where(fn ($q) => $q->whereBetween('due_date', [$from->toDateString(), $to->toDateString()])
                    ->orWhere(fn ($q) => $q->whereNull('due_date')->whereBetween('updated_at', [$from, $to])))
                ->get(['amount', 'due_date', 'updated_at'])
                ->map(fn ($p) => [$p->due_date ?? $p->updated_at, $p->amount]))
            ->concat(Proposal::where('status', Proposal::STATUS_FINAL)
                ->whereBetween('finalized_at', [$from, $to])
                ->where('advance_amount', '>', 0)
                ->get(['advance_amount', 'finalized_at'])
                ->map(fn ($p) => [$p->finalized_at, $p->advance_amount]))
            ->concat(ProposalPayment::whereBetween('paid_on', [$from->toDateString(), $to->toDateString()])
                ->get(['amount', 'paid_on'])
                ->map(fn ($p) => [$p->paid_on, $p->amount]))
            ->concat(B2bPayment::whereBetween('paid_on', [$from->toDateString(), $to->toDateString()])
                ->get(['amount', 'paid_on'])
                ->map(fn ($p) => [$p->paid_on, $p->amount]));

        return static::bucket($entries, $buckets);
    }

    /** @return array<string, float> expenses (general + marketing) per month, keyed Y-m */
    public static function expensesByMonth(int $count): array
    {
        [$from, $to, $buckets] = static::range($count);

        $entries = Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->get(['amount', 'expense_date'])
            ->map(fn ($e) => [$e->expense_date, $e->amount])
            ->concat(static::marketingByDay($from, $to));

        return static::bucket($entries, $buckets);
    }

    /** Marketing spend between two dates, as [date, amount] pairs. */
    public static function marketingByDay($from, $to): Collection
    {
        return MarketingExpense::whereDate('spent_on', '>=', $from)
            ->whereDate('spent_on', '<=', $to)
            ->get(['amount', 'spent_on'])
            ->map(fn ($e) => [$e->spent_on, $e->amount]);
    }

    /** Money still to be collected on work streams, final projects and B2B projects. */
    public static function pendingDues(): array
    {
        $workStreams = WorkStream::with('payments')->get()
            ->map(fn (WorkStream $w) => $w->balance)
            ->filter(fn ($balance) => $balance > 0);

        $projects = Proposal::where('status', Proposal::STATUS_FINAL)
            ->withSum('payments', 'amount')
            ->get()
            ->map(fn (Proposal $p) => $p->balance)
            ->filter(fn ($balance) => $balance > 0);

        $b2b = B2bProject::where('status', '!=', 'cancelled')
            ->withSum('payments', 'amount')
            ->get()
            ->map(fn (B2bProject $p) => $p->balance)
            ->filter(fn ($balance) => $balance > 0);

        return [
            'amount' => round($workStreams->sum() + $projects->sum() + $b2b->sum(), 2),
            'count' => $workStreams->count() + $projects->count() + $b2b->count(),
        ];
    }

    /** "Rs 1.2L"-style short label for tiles. */
    public static function short(float $value): string
    {
        $sign = $value < 0 ? '-' : '';
        $value = abs($value);

        return $sign.'₹'.match (true) {
            $value >= 1_00_00_000 => round($value / 1_00_00_000, 2).' Cr',
            $value >= 1_00_000 => round($value / 1_00_000, 2).' L',
            $value >= 1_000 => round($value / 1_000, 1).'K',
            default => number_format($value),
        };
    }

    /** Buckets [date, amount] pairs into the last $count months, keyed Y-m. */
    public static function bucketed(Collection $entries, int $count): array
    {
        return static::bucket($entries, static::range($count)[2]);
    }

    private static function range(int $count): array
    {
        $months = static::months($count);

        return [
            $months->first(),
            $months->last()->endOfMonth(),
            $months->mapWithKeys(fn ($m) => [$m->format('Y-m') => 0.0])->all(),
        ];
    }

    private static function bucket(Collection $entries, array $buckets): array
    {
        foreach ($entries as [$date, $amount]) {
            $key = $date?->format('Y-m');

            if ($key !== null && array_key_exists($key, $buckets)) {
                $buckets[$key] += (float) $amount;
            }
        }

        return array_map(fn ($v) => round($v, 2), $buckets);
    }
}
