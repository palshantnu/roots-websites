@php
    use App\Models\Proposal;
    use Illuminate\Support\Facades\Storage;

    $company = $proposal->company;
    $amount = fn ($value) => Proposal::formatAmount($value);

    $logo = null;
    if ($company->logo && Storage::disk('public')->exists($company->logo)) {
        $path = Storage::disk('public')->path($company->logo);
        $logo = 'data:'.mime_content_type($path).';base64,'.base64_encode(file_get_contents($path));
    }

    $contact = collect([$company->phone, $company->email, $company->website])->filter()->implode(' · ');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoiceNo }}</title>
    <style>
        @page { margin: 44px 54px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10.5px; color: #111; line-height: 1.5; }
        p { margin: 0; }
        table { border-collapse: collapse; width: 100%; }

        .head td { vertical-align: top; }
        .logo { max-height: 54px; max-width: 170px; margin-bottom: 4px; }
        .brand { font-size: 15px; font-weight: bold; color: #17365d; }
        .muted { color: #666; }
        .doc-title { font-size: 22px; font-weight: bold; color: #17365d; letter-spacing: 2px; text-align: right; }
        .meta { text-align: right; margin-top: 6px; }
        .meta td { padding: 1px 0 1px 10px; text-align: right; }

        .rule { border-top: 2px solid #17365d; margin: 14px 0; }

        .parties td { vertical-align: top; width: 50%; }
        .label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #17365d; font-weight: bold; margin-bottom: 3px; }

        table.lines { margin-top: 16px; }
        table.lines th { background: #17365d; color: #fff; text-align: left; padding: 5px 8px; font-size: 10px; }
        table.lines th.right { text-align: right; }
        table.lines td { border-bottom: 1px solid #d5deea; padding: 6px 8px; vertical-align: top; }
        table.lines tr.alt td { background: #f5f8fc; }
        .num { width: 28px; text-align: center; }
        .date { width: 80px; }
        .right { text-align: right; }

        table.totals { width: 46%; margin-left: 54%; margin-top: 12px; }
        table.totals td { padding: 4px 8px; }
        table.totals tr.grand td { background: #17365d; color: #fff; font-weight: bold; font-size: 11.5px; }
        table.totals tr.due td { font-weight: bold; color: #b42318; }
        table.totals tr.paid td { font-weight: bold; color: #067647; }

        .footer { margin-top: 36px; text-align: center; }
    </style>
</head>
<body>
    <table class="head">
        <tr>
            <td style="width: 58%">
                @if($logo)
                    <img src="{{ $logo }}" class="logo"><br>
                @endif
                <div class="brand">{{ strtoupper($company->name) }}</div>
                @if($company->tagline)
                    <div class="muted">{{ $company->tagline }}</div>
                @endif
                @if($company->address)
                    <div>{!! nl2br(e($company->address)) !!}</div>
                @endif
                @if($contact)
                    <div class="muted">{{ $contact }}</div>
                @endif
                @if($company->gstin)
                    <div><strong>GSTIN:</strong> {{ $company->gstin }}</div>
                @endif
            </td>
            <td>
                <div class="doc-title">INVOICE</div>
                <table class="meta">
                    <tr><td class="muted">Invoice no.</td><td><strong>{{ $invoiceNo }}</strong></td></tr>
                    <tr><td class="muted">Date</td><td>{{ $invoiceDate->format('d M Y') }}</td></tr>
                    <tr><td class="muted">Proposal no.</td><td>{{ $proposal->proposal_no }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

    <table class="parties">
        <tr>
            <td>
                <div class="label">Bill to</div>
                <p><strong>{{ $proposal->client_name }}</strong></p>
                @if($proposal->client_organization)
                    <p>{{ $proposal->client_organization }}</p>
                @endif
                @if($proposal->client_email)
                    <p class="muted">{{ $proposal->client_email }}</p>
                @endif
                @if($proposal->client_phone)
                    <p class="muted">{{ $proposal->client_phone }}</p>
                @endif
            </td>
            <td>
                <div class="label">Project</div>
                <p><strong>{{ $proposal->subject }}</strong></p>
                @if($proposal->service)
                    <p class="muted">Service: {{ $proposal->service->name }}</p>
                @endif
                <p class="muted">Project value: {{ $amount($proposal->final_amount) }}</p>
            </td>
        </tr>
    </table>

    <table class="lines">
        <thead>
            <tr>
                <th class="num">#</th>
                <th class="date">Date</th>
                <th>Description</th>
                <th>Mode</th>
                <th class="right" style="width: 100px">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lines as $line)
                <tr @class(['alt' => $loop->even])>
                    <td class="num">{{ $line['no'] }}</td>
                    <td class="date">{{ $line['date']?->format('d M Y') ?? '—' }}</td>
                    <td>
                        {{ $line['stage'] ?: 'Payment received' }}
                        @if($line['note'])
                            <div class="muted" style="font-size: 9.5px">{{ $line['note'] }}</div>
                        @endif
                    </td>
                    <td>{{ $line['mode'] ?? '—' }}</td>
                    <td class="right">{{ $amount($line['amount']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr class="grand">
            <td>{{ $combined ? 'Total received' : 'Amount received' }}</td>
            <td class="right">{{ $amount($invoiceTotal) }}</td>
        </tr>
        <tr>
            <td class="muted">Project value</td>
            <td class="right">{{ $amount($proposal->final_amount) }}</td>
        </tr>
        @unless($combined)
            <tr>
                <td class="muted">Received to date</td>
                <td class="right">{{ $amount($receivedToDate) }}</td>
            </tr>
        @endunless
        @if($balance > 0)
            <tr class="due">
                <td>Balance due</td>
                <td class="right">{{ $amount($balance) }}</td>
            </tr>
        @else
            <tr class="paid">
                <td>Balance</td>
                <td class="right">Fully paid</td>
            </tr>
        @endif
    </table>

    <div class="footer">
        <p><strong>Thank you for your business.</strong></p>
        @if($company->footer_note)
            <p><em>{{ $company->footer_note }}</em></p>
        @endif
        <p class="muted" style="font-size: 9px; margin-top: 6px">This is a computer-generated invoice and does not require a signature.</p>
    </div>
</body>
</html>
