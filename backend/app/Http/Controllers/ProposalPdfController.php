<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProposalPdfController extends Controller
{
    public function __invoke(Request $request, Proposal $proposal): Response
    {
        abort_unless($request->user()?->hasAccountPermission('proposals', 'view'), 403);

        $proposal->load('company');

        $filename = str_replace('/', '-', $proposal->proposal_no).'.pdf';

        return Pdf::loadView('pdf.proposal', ['proposal' => $proposal])
            ->setPaper('a4')
            ->download($filename);
    }
}
