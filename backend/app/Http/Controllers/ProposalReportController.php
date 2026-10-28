<?php

namespace App\Http\Controllers;

use App\Support\ProposalReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Support\XlsxExport;
use Symfony\Component\HttpFoundation\Response;

class ProposalReportController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless($request->user()?->hasAccountPermission('proposals', 'view'), 403);

        $report = ProposalReport::fromArray($request->query());

        return $request->query('format') === 'excel'
            ? $this->excel($report)
            : Pdf::loadView('pdf.proposal-report', ['report' => $report])
                ->setPaper('a4', 'landscape')
                ->download($report->filename('pdf'));
    }

    private function excel(ProposalReport $report): Response
    {
        $xlsx = (new XlsxExport())
            ->sheet('Summary')
            ->bold([$report->title()])
            ->row([$report->filterSummary()])
            ->blank();

        foreach ($report->totals() as $label => $value) {
            $xlsx->row([$label, $value]);
        }

        $groupHeader = fn (string $first) => [$first, 'Proposals', 'Final projects', 'Conversion %', 'Quoted', 'Final value', 'Received in period'];

        $xlsx->blank()->header($groupHeader('Created by'))->rows($report->byCreator())
            ->blank()->header($groupHeader('Service'))->rows($report->byService());

        $xlsx->sheet('Proposals')->header([
            'No.', 'Date', 'Company', 'Created by', 'Service', 'Client', 'Organization', 'Title', 'Status',
            'Quoted', 'Final amount', 'Advance', 'Received', 'Remaining', 'Finalised on',
        ]);
        foreach ($report->proposals as $p) {
            $final = $p->isFinal();
            $xlsx->row([
                $p->proposal_no,
                $p->proposal_date?->format('d-m-Y'),
                $p->company?->name,
                $p->creator?->name,
                $p->service?->name,
                $p->client_name,
                $p->client_organization,
                $p->subject,
                $final ? 'Final' : 'Proposal',
                (float) $p->total_amount,
                $final ? (float) $p->final_amount : null,
                $final ? (float) $p->advance_amount : null,
                $final ? $p->received : null,
                $final ? $p->balance : null,
                $p->finalized_at?->format('d-m-Y'),
            ]);
        }

        $xlsx->sheet('Payments received')
            ->header(['Date', 'Proposal no.', 'Client', 'Service', 'Created by', 'Stage', 'Mode', 'Amount'])
            ->rows($report->receipts->map(fn (array $r) => [
                $r['date']?->format('d-m-Y'), $r['proposal_no'], $r['client'], $r['service'], $r['creator'], $r['stage'], $r['mode'], $r['amount'],
            ]))
            ->bold(['', '', '', '', '', '', 'Total', $report->receipts->sum('amount')]);

        return $xlsx->download($report->filename('xlsx'));
    }
}
