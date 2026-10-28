<?php

namespace App\Http\Controllers;

use App\Models\B2bProject;
use App\Models\ProposalCompany;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invoice of a B2B project: one payment (?payment=<id>) or every payment
 * received so far in a single invoice (?payment=all).
 */
class B2bInvoiceController extends Controller
{
    public function __invoke(Request $request, B2bProject $project): Response
    {
        abort_unless($request->user()?->hasAccountPermission('b2b_projects', 'view'), 403);

        $project->load(['client', 'company', 'service']);

        $key = (string) $request->query('payment', 'all');
        $all = $project->receiptLines();
        $lines = $key === 'all' ? $all : $all->filter(fn (array $line) => (string) $line['key'] === $key)->values();

        abort_if($lines->isEmpty(), 404);

        $invoiceNo = 'INV/'.$project->project_no.($key === 'all' ? '' : '-'.$lines->first()['no']);
        $receivedToDate = $key === 'all' ? $all->sum('amount') : $all->where('no', '<=', $lines->first()['no'])->sum('amount');
        $client = $project->client;

        return Pdf::loadView('pdf.invoice', [
            // Letterhead of the billing company; the first active one if none was chosen.
            'company' => $project->company ?? ProposalCompany::where('is_active', true)->orderBy('sort_order')->firstOrFail(),
            'billTo' => [
                'name' => $client->company_name,
                'lines' => [
                    $client->contact_person ? 'Attn: '.$client->contact_person.($client->designation ? ', '.$client->designation : '') : null,
                    $client->fullAddress(),
                    $client->gstin ? 'GSTIN: '.$client->gstin : null,
                    collect([$client->email, $client->phone])->filter()->implode(' · '),
                ],
            ],
            'project' => [
                'title' => $project->title,
                'lines' => [$project->service ? 'Service: '.$project->service->name : null],
            ],
            'refLabel' => 'Project no.',
            'refNo' => $project->project_no,
            'projectValue' => (float) $project->value,
            'lines' => $lines,
            'combined' => $key === 'all',
            'invoiceNo' => $invoiceNo,
            'invoiceDate' => $key === 'all' ? now() : ($lines->first()['date'] ?? now()),
            'invoiceTotal' => $lines->sum('amount'),
            'receivedToDate' => $receivedToDate,
            'balance' => round((float) $project->value - $receivedToDate, 2),
        ])
            ->setPaper('a4')
            ->download(str_replace('/', '-', $invoiceNo).'.pdf');
    }
}
