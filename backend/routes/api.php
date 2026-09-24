<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PackagePageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PublishEnquiryController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('publication')->group(function () {
    Route::get('settings', [SettingController::class, 'index']);
    Route::get('search', [SearchController::class, 'index']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{slug}', [CategoryController::class, 'show']);

    Route::get('authors', [AuthorController::class, 'index']);
    Route::get('authors/{slug}', [AuthorController::class, 'show']);

    Route::get('books', [BookController::class, 'index']);
    Route::get('books/{slug}', [BookController::class, 'show']);

    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);

    Route::get('packages', [PackageController::class, 'index']);
    Route::get('package-pages/{slug}', [PackagePageController::class, 'show']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);

    Route::get('faqs', [FaqController::class, 'index']);

    Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:10,1');
    Route::post('publish-enquiries', [PublishEnquiryController::class, 'store'])->middleware('throttle:10,1');
    Route::post('newsletter', [NewsletterController::class, 'store'])->middleware('throttle:10,1');
    Route::post('orders', [OrderController::class, 'store'])->middleware('throttle:20,1');
});
