@php
    use App\Models\Proposal;
    use Illuminate\Support\Facades\Storage;

    $company = $proposal->company;
    $rich = fn (?string $html) => filled(strip_tags((string) $html)) ? str($html)->sanitizeHtml() : null;

    $logo = null;
    if ($company->logo && Storage::disk('public')->exists($company->logo)) {
        $path = Storage::disk('public')->path($company->logo);
        $logo = 'data:'.mime_content_type($path).';base64,'.base64_encode(file_get_contents($path));
    }

    $introduction = $rich($proposal->introduction);
    if ($introduction) {
        $introduction = str_replace('{company}', '<strong>'.e($company->name).'</strong>', $introduction);
    }

    $contact = collect([$company->phone, $company->email, $company->website])->filter()->implode(' · ');
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $proposal->proposal_no }}</title>
    <style>
        @page { margin: 48px 60px; }
        body { font-family: 'DejaVu Serif', serif; font-size: 11.5px; color: #111; line-height: 1.55; }
        p { margin: 0 0 6px; }
        .center { text-align: center; }

        .brand { font-size: 14px; font-weight: bold; color: #17365d; letter-spacing: 0.5px; }
        .tagline { color: #666; font-size: 12px; }
        .logo { max-height: 56px; max-width: 180px; margin-bottom: 4px; }

        .title { font-size: 12.5px; font-weight: bold; color: #17365d; margin: 20px 0 6px; }
        .fee { font-size: 12.5px; font-weight: bold; margin-bottom: 8px; }

        h2 { font-size: 13px; color: #17365d; margin: 16px 0 6px; }
        h2 .box { display: inline-block; width: 10px; height: 10px; background: #17365d; margin-right: 6px; }

        ul { list-style: none; padding-left: 0; margin: 0 0 6px; }
        ul li { margin-bottom: 7px; }
        ul li:before { content: "• "; }
        ol { margin: 0 0 6px; padding-left: 18px; }

        table.stages { width: 72%; margin-left: 3%; border-collapse: collapse; margin-top: 6px; }
        table.stages th { background: #17365d; color: #fff; text-align: left; padding: 3px 8px; font-size: 12px; }
        table.stages td { border: 1px solid #b8c7dd; padding: 3px 8px; }
        table.stages tr.alt td { background: #f2f6fb; }
        table.stages tr.total td { background: #e8eef7; font-weight: bold; }
        .stage { width: 50px; text-align: center; }
        .amount { width: 130px; }

        .footer { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="center">
        @if($logo)
            <img src="{{ $logo }}" class="logo"><br>
        @endif
        <div class="brand">{{ strtoupper($company->name) }}</div>
        @if($company->tagline)
            <div class="tagline">{{ $company->tagline }}</div>
        @endif
    </div>

    <div class="title">{{ $proposal->subject }}</div>
    <div class="fee">Total Package Fee: {{ Proposal::formatAmount($proposal->total) }}</div>

    @if($proposal->greeting)
        <p style="margin: 0">{{ $proposal->greeting }}</p>
    @endif
    @if($introduction)
        {!! $introduction !!}
    @endif

    @if($html = $rich($proposal->package_includes))
        <h2><span class="box"></span>Package Includes</h2>
        {!! $html !!}
    @endif

    @if(count($proposal->items ?? []))
        <h2 style="margin-top: 22px"><span class="box"></span>Payment Structure</h2>
        <table class="stages">
            <thead>
                <tr>
                    <th class="stage">Stage</th>
                    <th>Work</th>
                    <th class="amount">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposal->items as $item)
                    <tr @class(['alt' => $loop->even])>
                        <td class="stage">{{ $loop->iteration }}</td>
                        <td>{{ $item['work'] ?? '' }}</td>
                        <td>{{ Proposal::formatAmount($item['amount'] ?? 0) }}</td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td></td>
                    <td>TOTAL PACKAGE</td>
                    <td>{{ Proposal::formatAmount($proposal->total) }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @if($html = $rich($proposal->payment_terms))
        <h2><span class="box"></span>Payment Terms</h2>
        {!! $html !!}
    @endif

    @if($html = $rich($proposal->deliverables))
        <h2><span class="box"></span>Deliverables</h2>
        {!! $html !!}
    @endif

    <div class="center footer">
        <div class="brand" style="font-size: 12.5px">{{ strtoupper($company->name) }}</div>
        @if($company->tagline)
            <div style="margin-top: 6px">{{ $company->tagline }}</div>
        @endif
        @if($company->footer_note)
            <div><em>{{ $company->footer_note }}</em></div>
        @endif
        @if($contact)
            <div class="tagline" style="font-size: 10px; margin-top: 4px">{{ $contact }}</div>
        @endif
    </div>
</body>
</html>
