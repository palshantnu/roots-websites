@php
    use App\Models\Proposal;

    $amount = fn ($value) => Proposal::formatAmount($value);
    $totals = $report->totals();
    $services = $report->byService();
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
        table.tiles td { width: 16.66%; border: 1px solid #d5deea; padding: 6px 8px; }
        .tile-label { font-size: 8px; text-transform: uppercase; color: #666; }
        .tile-value { font-size: 13px; font-weight: bold; color: #17365d; }
        .badge { padding: 1px 5px; border-radius: 3px; font-size: 8px; }
        .final { background: #dcfae6; color: #067647; }
        .proposal { background: #eef2f6; color: #364152; }
        .empty { padding: 10px; color: #666; text-align: center; border: 1px dashed #d5deea; }
    </style>
</head>
<body>
    <h1>{{ $report->title() }}</h1>
    <div class="muted">{{ $report->filterSummary() }} · Generated {{ now()->format('d M Y, h:i A') }}</div>

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

    <h2>Service-wise</h2>
    @if($services->isEmpty())
        <div class="empty">No records in this period.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Service</th>
                    <th class="right">Proposals</th>
                    <th class="right">Final projects</th>
                    <th class="right">Quoted</th>
                    <th class="right">Final value</th>
                    <th class="right">Received in period</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $row)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $row['service'] }}</td>
                        <td class="right">{{ $row['proposals'] }}</td>
                        <td class="right">{{ $row['final'] }}</td>
                        <td class="right">{{ $amount($row['quoted']) }}</td>
                        <td class="right">{{ $amount($row['final_value']) }}</td>
                        <td class="right">{{ $amount($row['received']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Proposals ({{ $report->proposals->count() }})</h2>
    @if($report->proposals->isEmpty())
        <div class="empty">No proposals in this period.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Date</th>
                    <th>Company</th>
                    <th>Service</th>
                    <th>Client</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="right">Quoted</th>
                    <th class="right">Final</th>
                    <th class="right">Received</th>
                    <th class="right">Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->proposals as $p)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $p->proposal_no }}</td>
                        <td>{{ $p->proposal_date?->format('d M Y') }}</td>
                        <td>{{ $p->company?->code ?? $p->company?->name }}</td>
                        <td>{{ $p->service?->name ?? '—' }}</td>
                        <td>
                            {{ $p->client_name }}
                            @if($p->client_organization)
                                <div class="muted">{{ $p->client_organization }}</div>
                            @endif
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($p->subject, 45) }}</td>
                        <td><span class="badge {{ $p->isFinal() ? 'final' : 'proposal' }}">{{ $p->isFinal() ? 'Final' : 'Proposal' }}</span></td>
                        <td class="right">{{ $amount($p->total_amount) }}</td>
                        <td class="right">{{ $p->isFinal() ? $amount($p->final_amount) : '—' }}</td>
                        <td class="right">{{ $p->isFinal() ? $amount($p->received) : '—' }}</td>
                        <td class="right">{{ $p->isFinal() ? $amount($p->balance) : '—' }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="7">Total</td>
                    <td class="right">{{ $amount($totals['Quoted value']) }}</td>
                    <td class="right">{{ $amount($totals['Final value']) }}</td>
                    <td class="right">{{ $amount($report->proposals->filter->isFinal()->sum('received')) }}</td>
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
                    <th>Proposal no.</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Stage</th>
                    <th>Mode</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->receipts as $r)
                    <tr @class(['alt' => $loop->even])>
                        <td>{{ $r['date']?->format('d M Y') }}</td>
                        <td>{{ $r['proposal_no'] }}</td>
                        <td>{{ $r['client'] }}</td>
                        <td>{{ $r['service'] ?? '—' }}</td>
                        <td>{{ $r['stage'] ?? '—' }}</td>
                        <td>{{ $r['mode'] ?? '—' }}</td>
                        <td class="right">{{ $amount($r['amount']) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td colspan="6">Total received</td>
                    <td class="right">{{ $amount($report->receipts->sum('amount')) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</body>
</html>
