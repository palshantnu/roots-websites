<?php

namespace App\Support;

use App\Models\AccountService;
use App\Models\Proposal;
use App\Models\ProposalCompany;
use App\Models\ProposalPayment;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Proposals and money received in a period (this week, this month or custom
 * dates), filtered by company, service and status. Feeds the PDF and Excel.
 */
class ProposalReport
{
    public const PERIODS = ReportPeriod::OPTIONS;

    public const STATUSES = [
        'all' => 'All',
        Proposal::STATUS_PROPOSAL => 'Proposals only',
        Proposal::STATUS_FINAL => 'Final projects only',
    ];

    public CarbonImmutable $from;

    public CarbonImmutable $to;

    /** @var Collection<int, Proposal> */
    public Collection $proposals;

    /** @var Collection<int, array> */
    public Collection $receipts;

    public function __construct(
        public string $period = 'this_month',
        ?string $from = null,
        ?string $to = null,
        public ?int $companyId = null,
        public array $serviceIds = [],
        public string $status = 'all',
    ) {
        [$this->from, $this->to] = ReportPeriod::range($period, $from, $to);

        $this->proposals = $this->scoped(Proposal::query())
            ->with(['company', 'service'])
            ->withSum('payments', 'amount')
            ->whereDate('proposal_date', '>=', $this->from)
            ->whereDate('proposal_date', '<=', $this->to)
            ->when($this->status !== 'all', fn (Builder $q) => $q->where('status', $this->status))
            ->orderBy('proposal_date')
            ->orderBy('id')
            ->get();

        $this->receipts = $this->loadReceipts();
    }

    public static function fromArray(array $input): static
    {
        return new static(
            period: ReportPeriod::valid($input['period'] ?? null),
            from: $input['from'] ?? null,
            to: $input['to'] ?? null,
            companyId: filled($input['company'] ?? null) ? (int) $input['company'] : null,
            serviceIds: array_map('intval', array_filter((array) ($input['services'] ?? []))),
            status: array_key_exists($input['status'] ?? '', self::STATUSES) ? $input['status'] : 'all',
        );
    }

    private function scoped(Builder $query): Builder
    {
        return $query
            ->when($this->companyId, fn (Builder $q) => $q->where('proposal_company_id', $this->companyId))
            ->when($this->serviceIds, fn (Builder $q) => $q->whereIn('account_service_id', $this->serviceIds));
    }

    /** Advances (dated when finalised) and payments received inside the period. */
    private function loadReceipts(): Collection
    {
        $advances = $this->scoped(Proposal::query())
            ->with(['service'])
            ->where('status', Proposal::STATUS_FINAL)
            ->where('advance_amount', '>', 0)
            ->whereBetween('finalized_at', [$this->from, $this->to])
            ->get()
            ->map(fn (Proposal $p) => $this->receiptRow($p, $p->finalized_at, 'Advance', null, (float) $p->advance_amount));

        $payments = ProposalPayment::query()
            ->with(['proposal.service'])
            ->whereHas('proposal', fn (Builder $q) => $this->scoped($q))
            ->whereDate('paid_on', '>=', $this->from)
            ->whereDate('paid_on', '<=', $this->to)
            ->get()
            ->map(fn (ProposalPayment $pay) => $this->receiptRow($pay->proposal, $pay->paid_on, $pay->stage, $pay->mode, (float) $pay->amount));

        // By day; on the same day the advance comes before later payments.
        return $advances->concat($payments)->sortBy(fn (array $row) => $row['date']?->toDateString())->values();
    }

    private function receiptRow(Proposal $proposal, $date, ?string $stage, ?string $mode, float $amount): array
    {
        return [
            'date' => $date,
            'proposal_no' => $proposal->proposal_no,
            'client' => $proposal->client_name,
            'service' => $proposal->service?->name,
            'stage' => $stage,
            'mode' => $mode,
            'amount' => $amount,
        ];
    }

    public function title(): string
    {
        return 'Proposal report — '.ReportPeriod::label($this->period, $this->from, $this->to);
    }

    /** Human readable list of the filters applied, for the report header. */
    public function filterSummary(): string
    {
        return collect([
            self::PERIODS[$this->period],
            $this->companyId ? 'Company: '.ProposalCompany::whereKey($this->companyId)->value('name') : 'All companies',
            $this->serviceIds
                ? 'Service: '.AccountService::whereIn('id', $this->serviceIds)->orderBy('sort_order')->pluck('name')->implode(', ')
                : 'All services',
            'Status: '.self::STATUSES[$this->status],
        ])->implode(' · ');
    }

    public function totals(): array
    {
        $final = $this->proposals->filter->isFinal();

        return [
            'Proposals' => $this->proposals->count(),
            'Quoted value' => $this->proposals->sum(fn (Proposal $p) => (float) $p->total_amount),
            'Final projects' => $final->count(),
            'Final value' => $final->sum(fn (Proposal $p) => (float) $p->final_amount),
            'Received in period' => $this->receipts->sum('amount'),
            'Outstanding (these projects)' => $final->sum(fn (Proposal $p) => max($p->balance, 0)),
        ];
    }

    /** One row per service: how many proposals, quoted, final and received. */
    public function byService(): Collection
    {
        $received = $this->receipts->groupBy(fn (array $r) => $r['service'] ?? 'No service')->map->sum('amount');

        return $this->proposals
            ->groupBy(fn (Proposal $p) => $p->service?->name ?? 'No service')
            ->map(fn (Collection $group, string $name) => [
                'service' => $name,
                'proposals' => $group->count(),
                'final' => $group->filter->isFinal()->count(),
                'quoted' => $group->sum(fn (Proposal $p) => (float) $p->total_amount),
                'final_value' => $group->sum(fn (Proposal $p) => (float) $p->final_amount),
                'received' => (float) ($received[$name] ?? 0),
            ])
            ->union($received->except($this->proposals->map(fn (Proposal $p) => $p->service?->name ?? 'No service')->all())
                ->map(fn (float $sum, string $name) => [
                    'service' => $name, 'proposals' => 0, 'final' => 0, 'quoted' => 0, 'final_value' => 0, 'received' => $sum,
                ]))
            ->sortKeys()
            ->values();
    }

    public function filename(string $extension): string
    {
        return 'proposal-report-'.$this->from->format('Y-m-d').'-to-'.$this->to->format('Y-m-d').'.'.$extension;
    }
}
