<?php

namespace App\Http\Controllers;

use App\Models\MarketingExpense;
use App\Support\MarketingReport;
use App\Support\XlsxExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarketingReportController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless($request->user()?->hasAccountPermission('marketing', 'view'), 403);

        $report = MarketingReport::fromArray($request->query());

        return $request->query('format') === 'excel'
            ? $this->excel($report)
            : Pdf::loadView('pdf.marketing-report', ['report' => $report])
                ->setPaper('a4', 'landscape')
                ->download($report->filename('pdf'));
    }

    private function excel(MarketingReport $report): Response
    {
        $header = fn (string $first) => [$first, 'Entries', 'Amount', 'Share %', 'Leads', 'Cost per lead'];

        $xlsx = (new XlsxExport())
            ->sheet('Summary')
            ->bold([$report->title()])
            ->row([$report->filterSummary()])
            ->blank();

        foreach ($report->totals() as $label => $value) {
            $xlsx->row([$label, $value]);
        }

        foreach ([
            'Channel' => $report->byChannel(),
            'Paid to (person / agency)' => $report->byPaidTo(),
            'Campaign' => $report->byCampaign(),
            'Service' => $report->byService(),
            'Month' => $report->byMonth(),
        ] as $label => $rows) {
            if ($rows->isNotEmpty()) {
                $xlsx->blank()->header($header($label))->rows($rows);
            }
        }

        $xlsx->sheet('Expenses')->header([
            'Date', 'Title', 'Channel', 'Paid to', 'Campaign', 'Service', 'Company',
            'Runs from', 'Runs to', 'Mode', 'Reference', 'Leads', 'Amount', 'Added by', 'Note',
        ]);
        foreach ($report->expenses as $e) {
            $xlsx->row([
                $e->spent_on->format('d-m-Y'),
                $e->title,
                $e->channel?->name,
                $e->paid_to,
                $e->campaign,
                $e->service?->name,
                $e->company?->name,
                $e->runs_from?->format('d-m-Y'),
                $e->runs_to?->format('d-m-Y'),
                $e->mode,
                $e->reference,
                $e->leads,
                (float) $e->amount,
                $e->creator?->name,
                $e->note,
            ]);
        }
        $xlsx->bold(['', '', '', '', '', '', '', '', '', '', '', 'Total', $report->expenses->sum(fn (MarketingExpense $e) => (float) $e->amount)]);

        return $xlsx->download($report->filename('xlsx'));
    }
}
