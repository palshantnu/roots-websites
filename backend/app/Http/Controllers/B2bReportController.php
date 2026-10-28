<?php

namespace App\Http\Controllers;

use App\Models\B2bProject;
use App\Support\B2bReport;
use App\Support\XlsxExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** B2B business report, or one client's statement when ?client= is given. */
class B2bReportController extends Controller
{
    public function __invoke(Request $request): Response
    {
        abort_unless($request->user()?->hasAccountPermission('b2b_projects', 'view'), 403);

        $report = B2bReport::fromArray($request->query());

        return $request->query('format') === 'excel'
            ? $this->excel($report)
            : Pdf::loadView('pdf.b2b-report', ['report' => $report])
                ->setPaper('a4', 'landscape')
                ->download($report->filename('pdf'));
    }

    private function excel(B2bReport $report): Response
    {
        $groupHeader = fn (string $first) => [$first, 'Projects', 'Business value', 'Outstanding', 'Received in period'];

        $xlsx = (new XlsxExport())
            ->sheet('Summary')
            ->bold([$report->title()])
            ->row([$report->filterSummary()])
            ->blank();

        foreach ($report->totals() as $label => $value) {
            $xlsx->row([$label, $value]);
        }

        if (! $report->isStatement()) {
            $xlsx->blank()->header($groupHeader('Client'))->rows($report->byClient());
        }

        $xlsx->blank()->header($groupHeader('Service'))->rows($report->byService());

        $xlsx->sheet('Projects')->header([
            'Project no.', 'Client', 'Title', 'Service', 'Billed by', 'Assigned to', 'Status',
            'Start date', 'Due date', 'Completed on', 'Value', 'Received', 'Remaining',
        ]);
        foreach ($report->projects as $p) {
            $xlsx->row([
                $p->project_no,
                $p->client?->company_name,
                $p->title,
                $p->service?->name,
                $p->company?->name,
                $p->employee?->name,
                B2bProject::STATUSES[$p->status] ?? $p->status,
                $p->start_date?->format('d-m-Y'),
                $p->due_date?->format('d-m-Y'),
                $p->completed_on?->format('d-m-Y'),
                (float) $p->value,
                $p->received,
                $p->balance,
            ]);
        }

        $xlsx->sheet('Payments received')
            ->header(['Date', 'Project no.', 'Project', 'Client', 'Service', 'Mode', 'Reference', 'Amount'])
            ->rows($report->receipts->map(fn (array $r) => [
                $r['date']?->format('d-m-Y'), $r['project_no'], $r['project'], $r['client'], $r['service'], $r['mode'], $r['reference'], $r['amount'],
            ]))
            ->bold(['', '', '', '', '', '', 'Total', $report->receipts->sum('amount')]);

        return $xlsx->download($report->filename('xlsx'));
    }
}
