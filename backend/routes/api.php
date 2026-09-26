<?php

use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PackagePageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PublishEnquiryController;
use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SectionItemController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SitePageController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('publication')->middleware('site:publications')->group(function () {
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

Route::prefix('research')->middleware('site:research')->name('research.')->group(function () {
    Route::get('settings', [SettingController::class, 'index']);

    Route::get('pages', [SitePageController::class, 'index']);
    Route::get('pages/{slug}', [SitePageController::class, 'show']);

    Route::get('sections', [SectionItemController::class, 'index']);

    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);

    Route::get('testimonials', [TestimonialController::class, 'index']);

    Route::get('faqs', [FaqController::class, 'index']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);

    Route::get('samples', [SampleController::class, 'index']);
    Route::get('samples/{slug}', [SampleController::class, 'show']);
    Route::get('samples/{slug}/file', [SampleController::class, 'file'])->middleware('throttle:60,1')->name('samples.file');
    Route::get('samples/{slug}/download', [SampleController::class, 'download'])->middleware('throttle:60,1')->name('samples.download');

    Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact');
});

Route::prefix('it')->middleware('site:it')->name('it.')->group(function () {
    Route::get('settings', [SettingController::class, 'index']);

    Route::get('pages', [SitePageController::class, 'index']);
    Route::get('pages/{slug}', [SitePageController::class, 'show']);

    Route::get('sections', [SectionItemController::class, 'index']);

    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);

    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{slug}', [ProjectController::class, 'show']);

    Route::get('case-studies', [CaseStudyController::class, 'index']);
    Route::get('case-studies/{slug}', [CaseStudyController::class, 'show']);

    Route::get('testimonials', [TestimonialController::class, 'index']);

    Route::get('faqs', [FaqController::class, 'index']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);

    Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact');
});
