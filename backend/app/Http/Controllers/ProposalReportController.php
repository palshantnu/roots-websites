<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Support\ProposalReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
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
        $path = tempnam(sys_get_temp_dir(), 'report').'.xlsx';
        $bold = (new Style())->setFontBold();
        $header = (new Style())->setFontBold()->setFontColor('FFFFFF')->setBackgroundColor('17365D');

        $writer = new Writer();
        $writer->openToFile($path);

        // Sheet 1 — summary.
        $writer->getCurrentSheet()->setName('Summary');
        $writer->addRow(Row::fromValues([$report->title()], $bold));
        $writer->addRow(Row::fromValues([$report->filterSummary()]));
        $writer->addRow(Row::fromValues([]));
        foreach ($report->totals() as $label => $value) {
            $writer->addRow(Row::fromValues([$label, $value]));
        }
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValues(['Service', 'Proposals', 'Final projects', 'Quoted', 'Final value', 'Received in period'], $header));
        foreach ($report->byService() as $row) {
            $writer->addRow(Row::fromValues(array_values($row)));
        }

        // Sheet 2 — proposals.
        $writer->addNewSheetAndMakeItCurrent()->setName('Proposals');
        $writer->addRow(Row::fromValues([
            'No.', 'Date', 'Company', 'Service', 'Client', 'Organization', 'Title', 'Status',
            'Quoted', 'Final amount', 'Advance', 'Received', 'Remaining', 'Finalised on',
        ], $header));
        foreach ($report->proposals as $p) {
            $final = $p->isFinal();
            $writer->addRow(Row::fromValues([
                $p->proposal_no,
                $p->proposal_date?->format('d-m-Y'),
                $p->company?->name,
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
            ]));
        }

        // Sheet 3 — money received in the period.
        $writer->addNewSheetAndMakeItCurrent()->setName('Payments received');
        $writer->addRow(Row::fromValues(['Date', 'Proposal no.', 'Client', 'Service', 'Stage', 'Mode', 'Amount'], $header));
        foreach ($report->receipts as $r) {
            $writer->addRow(Row::fromValues([
                $r['date']?->format('d-m-Y'), $r['proposal_no'], $r['client'], $r['service'], $r['stage'], $r['mode'], $r['amount'],
            ]));
        }
        $writer->addRow(Row::fromValues(['', '', '', '', '', 'Total', $report->receipts->sum('amount')], $bold));

        $writer->close();

        return response()->download($path, $report->filename('xlsx'))->deleteFileAfterSend();
    }
}
