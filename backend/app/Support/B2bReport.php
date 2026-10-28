<?php

namespace App\Support;

use App\Models\AccountService;
use App\Models\B2bClient;
use App\Models\B2bPayment;
use App\Models\B2bProject;
use App\Models\ProposalCompany;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * B2B business in a period: projects started in it and payments received in
 * it, filtered by client, service, company and status. With a client it is
 * that client's statement of account. Feeds the PDF and Excel.
 */
class B2bReport
{
    public CarbonImmutable $from;

    public CarbonImmutable $to;

    public ?B2bClient $client;

    /** @var Collection<int, B2bProject> */
    public Collection $projects;

    /** @var Collection<int, array> */
    public Collection $receipts;

    public function __construct(
        public string $period = 'this_month',
        ?string $from = null,
        ?string $to = null,
        public ?int $clientId = null,
        public array $serviceIds = [],
        public ?int $companyId = null,
        public ?string $status = null,
    ) {
        [$this->from, $this->to] = ReportPeriod::range($period, $from, $to);
        $this->client = $clientId ? B2bClient::find($clientId) : null;

        $this->projects = $this->scoped(B2bProject::query())
            ->with(['client', 'service', 'company', 'employee'])
            ->withSum('payments', 'amount')
            ->where(fn (Builder $q) => $q
                ->whereDate('start_date', '>=', $this->from)->whereDate('start_date', '<=', $this->to)
                ->orWhere(fn (Builder $q) => $q->whereNull('start_date')->whereBetween('created_at', [$this->from, $this->to])))
            ->when($this->status, fn (Builder $q) => $q->where('status', $this->status))
            ->orderBy('start_date')
            ->orderBy('id')
            ->get();

        $this->receipts = B2bPayment::query()
            ->with(['project.client', 'project.service'])
            ->whereHas('project', fn (Builder $q) => $this->scoped($q))
            ->whereDate('paid_on', '>=', $this->from)
            ->whereDate('paid_on', '<=', $this->to)
            ->orderBy('paid_on')
            ->orderBy('id')
            ->get()
            ->map(fn (B2bPayment $pay) => [
                'date' => $pay->paid_on,
                'project_no' => $pay->project->project_no,
                'project' => $pay->project->title,
                'client' => $pay->project->client?->company_name,
                'service' => $pay->project->service?->name,
                'mode' => $pay->mode,
                'reference' => $pay->reference,
                'amount' => (float) $pay->amount,
            ]);
    }

    public static function fromArray(array $input): static
    {
        return new static(
            period: ReportPeriod::valid($input['period'] ?? null, filled($input['client'] ?? null) ? 'all_time' : 'this_month'),
            from: $input['from'] ?? null,
            to: $input['to'] ?? null,
            clientId: filled($input['client'] ?? null) ? (int) $input['client'] : null,
            serviceIds: array_map('intval', array_filter((array) ($input['services'] ?? []))),
            companyId: filled($input['company'] ?? null) ? (int) $input['company'] : null,
            status: array_key_exists($input['status'] ?? '', B2bProject::STATUSES) ? $input['status'] : null,
        );
    }

    private function scoped(Builder $query): Builder
    {
        return $query
            ->when($this->clientId, fn (Builder $q) => $q->where('b2b_client_id', $this->clientId))
            ->when($this->companyId, fn (Builder $q) => $q->where('proposal_company_id', $this->companyId))
            ->when($this->serviceIds, fn (Builder $q) => $q->whereIn('account_service_id', $this->serviceIds));
    }

    public function isStatement(): bool
    {
        return $this->client !== null;
    }

    public function title(): string
    {
        return ($this->isStatement() ? 'Statement of account — '.$this->client->company_name : 'B2B business report')
            .' · '.ReportPeriod::label($this->period, $this->from, $this->to);
    }

    public function filterSummary(): string
    {
        return collect([
            ReportPeriod::OPTIONS[$this->period],
            $this->isStatement() ? null : 'All clients',
            $this->companyId ? 'Billed by: '.ProposalCompany::whereKey($this->companyId)->value('name') : null,
            $this->serviceIds
                ? 'Service: '.AccountService::whereIn('id', $this->serviceIds)->orderBy('sort_order')->pluck('name')->implode(', ')
                : 'All services',
            'Status: '.($this->status ? B2bProject::STATUSES[$this->status] : 'All'),
        ])->filter()->implode(' · ');
    }

    public function totals(): array
    {
        $active = $this->projects->where('status', '!=', 'cancelled');

        return [
            'Projects' => $this->projects->count(),
            'Completed' => $this->projects->where('status', 'completed')->count(),
            'Business value' => $active->sum(fn (B2bProject $p) => (float) $p->value),
            'Received (these projects)' => $active->sum(fn (B2bProject $p) => $p->received),
            'Outstanding (these projects)' => $active->sum(fn (B2bProject $p) => max($p->balance, 0)),
            'Received in period' => $this->receipts->sum('amount'),
        ];
    }

    /** Overall business per client: projects, value, received and outstanding. */
    public function byClient(): Collection
    {
        return $this->groupProjects(fn (B2bProject $p) => $p->client?->company_name ?? '—', 'client');
    }

    public function byService(): Collection
    {
        return $this->groupProjects(fn (B2bProject $p) => $p->service?->name ?? 'No service', 'service');
    }

    private function groupProjects(callable $key, string $receiptKey): Collection
    {
        $received = $this->receipts->groupBy(fn (array $r) => $r[$receiptKey] ?? ($receiptKey === 'service' ? 'No service' : '—'))->map->sum('amount');
        $names = $this->projects->map($key)->merge($received->keys())->unique()->sort()->values();

        return $names->map(function (string $name) use ($key, $received) {
            $group = $this->projects->filter(fn (B2bProject $p) => $key($p) === $name)->where('status', '!=', 'cancelled');

            return [
                'name' => $name,
                'projects' => $group->count(),
                'value' => $group->sum(fn (B2bProject $p) => (float) $p->value),
                'outstanding' => $group->sum(fn (B2bProject $p) => max($p->balance, 0)),
                'received_in_period' => (float) ($received[$name] ?? 0),
            ];
        });
    }

    public function filename(string $extension): string
    {
        $name = $this->isStatement() ? 'statement-'.str($this->client->company_name)->slug() : 'b2b-report';

        return $name.'-'.$this->from->format('Y-m-d').'-to-'.$this->to->format('Y-m-d').'.'.$extension;
    }
}
