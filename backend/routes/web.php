<?php

use App\Http\Controllers\ProposalInvoiceController;
use App\Http\Controllers\ProposalPdfController;
use App\Http\Controllers\ProposalReportController;
use App\Http\Controllers\PublishEnquiryDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/publish-enquiries/{publishEnquiry}/manuscript', PublishEnquiryDownloadController::class)
    ->middleware('auth')
    ->name('publish-enquiries.manuscript');

// Registered before {proposal} routes so "report" is not read as a proposal id.
Route::get('/accounts/proposals/report/download', ProposalReportController::class)
    ->middleware('auth')
    ->name('proposals.report');

Route::get('/accounts/proposals/{proposal}/invoice', ProposalInvoiceController::class)
    ->middleware('auth')
    ->name('proposals.invoice');

Route::get('/accounts/proposals/{proposal}/pdf', ProposalPdfController::class)
    ->middleware('auth')
    ->name('proposals.pdf');
