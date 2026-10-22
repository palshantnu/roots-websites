<?php

use App\Http\Controllers\ProposalPdfController;
use App\Http\Controllers\PublishEnquiryDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/publish-enquiries/{publishEnquiry}/manuscript', PublishEnquiryDownloadController::class)
    ->middleware('auth')
    ->name('publish-enquiries.manuscript');

Route::get('/accounts/proposals/{proposal}/pdf', ProposalPdfController::class)
    ->middleware('auth')
    ->name('proposals.pdf');
