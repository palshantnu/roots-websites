@php
    use App\Models\Proposal;

    $amount = fn ($value) => Proposal::formatAmount($value);
    $groups = array_filter([
        'Channel-wise' => $report->byChannel(),
        'Paid to (persons & agencies)' => $report->byPaidTo(),
        'Campaign-wise' => $report->byCampaign(),
        'Service-wise' => $report->byService(),
        'Month-wise' => $report->byMonth()->count() > 1 ? $report->byMonth() : null,
    ], fn ($rows) => $rows && $rows->isNotEmpty());
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
        .muted { color: #666; }
        .right { text-align: right; }
        table { border-collapse: collapse; width: 100%; }
        table.data th { background: #17365d; color: #fff; text-align: left; padding: 4px 5px; font-size: 8.5px; }
        table.data th.right { text-align: right; }
        table.data td { border-bottom: 1px solid #d5deea; padding: 4px 5px; vertical-align: top; }
        table.data tr.alt td { background: #f5f8fc; }
        table.data tr.total td { background: #e8eef7; font-weight: bold; }
        table.tiles td { width: 20%; border: 1px solid #d5deea; padding: 6px 8px; }
        .tile-label { font-size: 8px; text-transform: uppercase; color: #666; }
        .tile-value { font-size: 13px; font-weight: bold; color: #17365d; }
        .bar { background: #eb6834; height: 6px; display: inline-block; }
        .empty { padding: 10px; color: #666; text-align: center; border: 1px dashed #d5deea; }
    </style>
</head>
<body>
    <h1>{{ $report->title() }}</h1>
    <div class="muted">{{ $report->filterSummary() }} · Generated {{ now()->format('d M Y, h:i A') }}</div>

    <table class="tiles" style="margin-top: 12px">
        <tr>
            @foreach($report->totals() as $label => $value)
                <td>
                    <div class="tile-label">{{ $label }}</div>
                    <div class="tile-value">
                        @if(is_string($value)) {{ $value }}
                        @elseif(is_int($value)) {{ $value }}
                        @elseif($value === null) —
                        @else {{ $amount($value) }}
                        @endif
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

    @foreach($groups as $heading => $rows)
        <h2>{{ $heading }}</h2>
        <table class="data">
            <thead>
                <tr>
                    <th style="width: 30%">{{ str($heading)->before('-wise')->before(' (') }}</th>
                    <th class="right">Entries</th>
                    <th class="right">Amount</th>
                    <th style="width: 22%">Share</th>
                    <th class="right">Leads</th>
                    <th class="right">Cost per lead</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $row['name'] }}</td>
                        <td class="right">{{ $row['entries'] }}</td>
                        <td class="right">{{ $amount($row['amount']) }}</td>
                        <td><span class="bar" style="width: {{ max($row['share'], 1) }}px"></span> {{ $row['share'] }}%</td>
                        <td class="right">{{ $row['leads'] ?: '—' }}</td>
                        <td class="right">{{ $row['cost_per_lead'] !== null ? $amount($row['cost_per_lead']) : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <h2>Expenses ({{ $report->expenses->count() }})</h2>
    @if($report->expenses->isEmpty())
        <div class="empty">No marketing expenses in this period.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Channel</th>
                    <th>Paid to</th>
                    <th>Campaign</th>
                    <th>Service</th>
                    <th>Runs</th>
                    <th>Mode / ref.</th>
                    <th class="right">Leads</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->expenses as $e)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $e->spent_on->format('d M Y') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($e->title, 40) }}</td>
                        <td>{{ $e->channel?->name }}</td>
                        <td>{{ $e->paid_to ?? '—' }}</td>
                        <td>{{ $e->campaign ?? '—' }}</td>
                        <td>{{ $e->service?->name ?? '—' }}</td>
                        <td>
                            @if($e->runs_from || $e->runs_to)
                                {{ $e->runs_from?->format('d M') ?? '…' }} – {{ $e->runs_to?->format('d M') ?? '…' }}
                            @else — @endif
                        </td>
                        <td>{{ collect([$e->mode, $e->reference])->filter()->implode(' · ') ?: '—' }}</td>
                        <td class="right">{{ $e->leads ?? '—' }}</td>
                        <td class="right">{{ $amount($e->amount) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="8">Total spent</td>
                    <td class="right">{{ $report->expenses->sum('leads') ?: '' }}</td>
                    <td class="right">{{ $amount($report->totals()['Total spent']) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</body>
</html>
