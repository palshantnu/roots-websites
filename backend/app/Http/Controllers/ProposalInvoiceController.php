<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Invoice of a final project: either one payment (?payment=advance|<id>) or
 * every payment received so far in a single invoice (?payment=all).
 */
class ProposalInvoiceController extends Controller
{
    public function __invoke(Request $request, Proposal $proposal): Response
    {
        abort_unless($request->user()?->hasAccountPermission('proposals', 'view'), 403);
        abort_unless($proposal->isFinal(), 404);

        $proposal->load(['company', 'service']);

        $key = (string) $request->query('payment', 'all');
        $lines = $proposal->receiptLines();

        if ($key !== 'all') {
            $lines = $lines->filter(fn (array $line) => (string) $line['key'] === $key)->values();
        }

        abort_if($lines->isEmpty(), 404);

        $base = $proposal->proposal_no;
        $invoiceNo = $key === 'all' ? 'INV/'.$base : 'INV/'.$base.'-'.$lines->first()['no'];
        $invoiceDate = $key === 'all' ? now() : ($lines->first()['date'] ?? now());

        // Received up to and including this invoice — the balance as it stood then.
        $receivedToDate = $key === 'all'
            ? $proposal->received
            : $proposal->receiptLines()->where('no', '<=', $lines->first()['no'])->sum('amount');

        return Pdf::loadView('pdf.invoice', [
            'proposal' => $proposal,
            'lines' => $lines,
            'combined' => $key === 'all',
            'invoiceNo' => $invoiceNo,
            'invoiceDate' => $invoiceDate,
            'invoiceTotal' => $lines->sum('amount'),
            'receivedToDate' => $receivedToDate,
            'balance' => round((float) $proposal->final_amount - $receivedToDate, 2),
        ])
            ->setPaper('a4')
            ->download(str_replace('/', '-', $invoiceNo).'.pdf');
    }
}
