@php
    use App\Models\B2bProject;
    use App\Models\Proposal;

    $amount = fn ($value) => Proposal::formatAmount($value);
    $totals = $report->totals();
    $client = $report->client;
    $groups = array_filter([
        'Client-wise business' => $report->isStatement() ? null : $report->byClient(),
        'Service-wise business' => $report->byService(),
    ]);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report->title() }}</title>
    <style>
        @page { margin: 32px 36px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #111; }
        h1 { font-size: 15px; color: #17365d; margin: 0; }
        h2 { font-size: 11.5px; color: #17365d; margin: 16px 0 6px; }
        p { margin: 0; }
        .muted { color: #666; }
        .right { text-align: right; }
        table { border-collapse: collapse; width: 100%; }
        table.data th { background: #17365d; color: #fff; text-align: left; padding: 4px 5px; font-size: 8.5px; }
        table.data th.right { text-align: right; }
        table.data td { border-bottom: 1px solid #d5deea; padding: 4px 5px; vertical-align: top; }
        table.data tr.alt td { background: #f5f8fc; }
        table.data tr.total td { background: #e8eef7; font-weight: bold; }
        table.tiles td { width: 16.66%; border: 1px solid #d5deea; padding: 6px 8px; }
        .tile-label { font-size: 8px; text-transform: uppercase; color: #666; }
        .tile-value { font-size: 13px; font-weight: bold; color: #17365d; }
        .client { margin-top: 10px; padding: 8px 10px; background: #f5f8fc; border-left: 3px solid #17365d; }
        .badge { padding: 1px 5px; border-radius: 3px; font-size: 8px; background: #eef2f6; color: #364152; }
        .completed { background: #dcfae6; color: #067647; }
        .in_progress { background: #fef0c7; color: #93370d; }
        .cancelled { background: #fee4e2; color: #b42318; }
        .on_hold { background: #e0f2fe; color: #026aa2; }
        .due { color: #b42318; font-weight: bold; }
        .empty { padding: 10px; color: #666; text-align: center; border: 1px dashed #d5deea; }
    </style>
</head>
<body>
    <h1>{{ $report->title() }}</h1>
    <div class="muted">{{ $report->filterSummary() }} · Generated {{ now()->format('d M Y, h:i A') }}</div>

    @if($client)
        <div class="client">
            <p><strong>{{ $client->company_name }}</strong>
                @if($client->contact_person) · {{ $client->contact_person }}@if($client->designation), {{ $client->designation }}@endif @endif
            </p>
            @if($address = $client->fullAddress())
                <p class="muted">{{ $address }}</p>
            @endif
            <p class="muted">
                {{ collect([$client->email, $client->phone, $client->gstin ? 'GSTIN '.$client->gstin : null])->filter()->implode(' · ') }}
            </p>
        </div>
    @endif

    <table class="tiles" style="margin-top: 12px">
        <tr>
            @foreach($totals as $label => $value)
                <td>
                    <div class="tile-label">{{ $label }}</div>
                    <div class="tile-value">{{ is_int($value) ? $value : $amount($value) }}</div>
                </td>
            @endforeach
        </tr>
    </table>

    @foreach($groups as $heading => $rows)
        <h2>{{ $heading }}</h2>
        @if($rows->isEmpty())
            <div class="empty">No records in this period.</div>
        @else
            <table class="data">
                <thead>
                    <tr>
                        <th>{{ str($heading)->before('-wise') }}</th>
                        <th class="right">Projects</th>
                        <th class="right">Business value</th>
                        <th class="right">Outstanding</th>
                        <th class="right">Received in period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr @class(['alt' => $loop->even])>
                            <td>{{ $row['name'] }}</td>
                            <td class="right">{{ $row['projects'] }}</td>
                            <td class="right">{{ $amount($row['value']) }}</td>
                            <td class="right">{{ $amount($row['outstanding']) }}</td>
                            <td class="right">{{ $amount($row['received_in_period']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <h2>Projects ({{ $report->projects->count() }})</h2>
    @if($report->projects->isEmpty())
        <div class="empty">No projects in this period.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Project no.</th>
                    @unless($client)<th>Client</th>@endunless
                    <th>Title</th>
                    <th>Service</th>
                    <th>Assigned to</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>Due</th>
                    <th class="right">Value</th>
                    <th class="right">Received</th>
                    <th class="right">Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->projects as $p)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $p->project_no }}</td>
                        @unless($client)<td>{{ $p->client?->company_name }}</td>@endunless
                        <td>{{ \Illuminate\Support\Str::limit($p->title, 45) }}</td>
                        <td>{{ $p->service?->name ?? '—' }}</td>
                        <td>{{ $p->employee?->name ?? '—' }}</td>
                        <td><span class="badge {{ $p->status }}">{{ B2bProject::STATUSES[$p->status] ?? $p->status }}</span></td>
                        <td>{{ $p->start_date?->format('d M Y') ?? '—' }}</td>
                        <td>{{ $p->due_date?->format('d M Y') ?? '—' }}</td>
                        <td class="right">{{ $amount($p->value) }}</td>
                        <td class="right">{{ $amount($p->received) }}</td>
                        <td @class(['right', 'due' => $p->balance > 0 && $p->status !== 'cancelled'])>{{ $amount($p->balance) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="{{ $client ? 7 : 8 }}">Total (excluding cancelled)</td>
                    <td class="right">{{ $amount($totals['Business value']) }}</td>
                    <td class="right">{{ $amount($totals['Received (these projects)']) }}</td>
                    <td class="right">{{ $amount($totals['Outstanding (these projects)']) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <h2>Payments received in this period ({{ $report->receipts->count() }})</h2>
    @if($report->receipts->isEmpty())
        <div class="empty">No payments received in this period.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Project no.</th>
                    <th>Project</th>
                    @unless($client)<th>Client</th>@endunless
                    <th>Service</th>
                    <th>Mode</th>
                    <th>Reference</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->receipts as $r)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $r['date']?->format('d M Y') }}</td>
                        <td>{{ $r['project_no'] }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($r['project'], 40) }}</td>
                        @unless($client)<td>{{ $r['client'] }}</td>@endunless
                        <td>{{ $r['service'] ?? '—' }}</td>
                        <td>{{ $r['mode'] ?? '—' }}</td>
                        <td>{{ $r['reference'] ?? '—' }}</td>
                        <td class="right">{{ $amount($r['amount']) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="{{ $client ? 6 : 7 }}">Total received</td>
                    <td class="right">{{ $amount($report->receipts->sum('amount')) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</body>
</html>
