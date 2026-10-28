<?php

namespace App\Support;

use App\Models\AccountService;
use App\Models\MarketingChannel;
use App\Models\MarketingExpense;
use App\Models\ProposalCompany;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Marketing spend in a period, filtered by channel, service and company:
 * channel-wise, person/agency-wise, campaign-wise, service-wise and monthly.
 */
class MarketingReport
{
    public CarbonImmutable $from;

    public CarbonImmutable $to;

    /** @var Collection<int, MarketingExpense> */
    public Collection $expenses;

    public function __construct(
        public string $period = 'this_month',
        ?string $from = null,
        ?string $to = null,
        public array $channelIds = [],
        public array $serviceIds = [],
        public ?int $companyId = null,
    ) {
        [$this->from, $this->to] = ReportPeriod::range($period, $from, $to);

        $this->expenses = MarketingExpense::query()
            ->with(['channel', 'service', 'company', 'creator'])
            ->whereDate('spent_on', '>=', $this->from)
            ->whereDate('spent_on', '<=', $this->to)
            ->when($this->channelIds, fn (Builder $q) => $q->whereIn('marketing_channel_id', $this->channelIds))
            ->when($this->serviceIds, fn (Builder $q) => $q->whereIn('account_service_id', $this->serviceIds))
            ->when($this->companyId, fn (Builder $q) => $q->where('proposal_company_id', $this->companyId))
            ->orderBy('spent_on')
            ->orderBy('id')
            ->get();
    }

    public static function fromArray(array $input): static
    {
        $ids = fn (string $key) => array_map('intval', array_filter((array) ($input[$key] ?? [])));

        return new static(
            period: ReportPeriod::valid($input['period'] ?? null),
            from: $input['from'] ?? null,
            to: $input['to'] ?? null,
            channelIds: $ids('channels'),
            serviceIds: $ids('services'),
            companyId: filled($input['company'] ?? null) ? (int) $input['company'] : null,
        );
    }

    public function title(): string
    {
        return 'Marketing expense report — '.ReportPeriod::label($this->period, $this->from, $this->to);
    }

    public function filterSummary(): string
    {
        return collect([
            ReportPeriod::OPTIONS[$this->period],
            $this->channelIds
                ? 'Channel: '.MarketingChannel::whereIn('id', $this->channelIds)->orderBy('sort_order')->pluck('name')->implode(', ')
                : 'All channels',
            $this->serviceIds
                ? 'Service: '.AccountService::whereIn('id', $this->serviceIds)->orderBy('sort_order')->pluck('name')->implode(', ')
                : 'All services',
            $this->companyId ? 'Company: '.ProposalCompany::whereKey($this->companyId)->value('name') : 'All companies',
        ])->implode(' · ');
    }

    public function totals(): array
    {
        $spent = $this->spent($this->expenses);
        $leads = (int) $this->expenses->sum('leads');
        $top = $this->byChannel()->sortByDesc('amount')->first();

        return [
            'Total spent' => $spent,
            'Entries' => $this->expenses->count(),
            'Leads' => $leads,
            'Cost per lead' => MarketingExpense::costPerLead($spent, $leads),
            'Top channel' => $top['name'] ?? '—',
        ];
    }

    public function byChannel(): Collection
    {
        return $this->group(fn (MarketingExpense $e) => $e->channel?->name ?? '—');
    }

    /** Persons and agencies who were paid, biggest first. */
    public function byPaidTo(): Collection
    {
        return $this->group(fn (MarketingExpense $e) => $e->paid_to ?: null)->sortByDesc('amount')->values();
    }

    public function byCampaign(): Collection
    {
        return $this->group(fn (MarketingExpense $e) => $e->campaign ?: null)->sortByDesc('amount')->values();
    }

    public function byService(): Collection
    {
        return $this->group(fn (MarketingExpense $e) => $e->service?->name ?? 'Not tagged');
    }

    public function byMonth(): Collection
    {
        return $this->expenses->toBase()
            ->groupBy(fn (MarketingExpense $e) => $e->spent_on->format('Y-m'))
            ->sortKeys()
            ->map(fn (Collection $rows, string $month) => $this->row(CarbonImmutable::parse($month.'-01')->format('M Y'), $rows))
            ->values();
    }

    /** Rows of name / entries / amount / share / leads / cost per lead; null keys are skipped. */
    private function group(callable $key): Collection
    {
        return $this->expenses->toBase()
            ->groupBy(fn (MarketingExpense $e) => $key($e) ?? '')
            ->except([''])
            ->map(fn (Collection $rows, string $name) => $this->row($name, $rows))
            ->sortKeys()
            ->values();
    }

    private function row(string $name, Collection $rows): array
    {
        $amount = $this->spent($rows);
        $total = $this->spent($this->expenses);
        $leads = (int) $rows->sum('leads');

        return [
            'name' => $name,
            'entries' => $rows->count(),
            'amount' => $amount,
            'share' => $total > 0 ? round($amount / $total * 100) : 0,
            'leads' => $leads,
            'cost_per_lead' => MarketingExpense::costPerLead($amount, $leads),
        ];
    }

    private function spent(Collection $rows): float
    {
        return round((float) $rows->sum(fn (MarketingExpense $e) => (float) $e->amount), 2);
    }

    public function filename(string $extension): string
    {
        return 'marketing-report-'.$this->from->format('Y-m-d').'-to-'.$this->to->format('Y-m-d').'.'.$extension;
    }
}
