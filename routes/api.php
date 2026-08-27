<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CareerController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\CoverageController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\NetworkStatusController;
use App\Http\Controllers\Api\V1\PackageController;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes — /api/v1/*
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login');

        Route::middleware('auth:jwt')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/featured', [PostController::class, 'featured'])->name('featured');
        Route::get('/trending', [PostController::class, 'trending'])->name('trending');
        Route::get('/{slug}', [PostController::class, 'show'])->name('show');
        Route::get('/{postId}/related', [PostController::class, 'related'])->whereNumber('postId')->name('related');
    });

    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
    });

    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/popular', [PackageController::class, 'popular'])->name('popular');
        Route::get('/{slug}', [PackageController::class, 'show'])->name('show');
    });

    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/featured', [PortfolioController::class, 'featured'])->name('featured');
        Route::get('/{slug}', [PortfolioController::class, 'show'])->name('show');
    });

    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/featured', [TestimonialController::class, 'featured'])->name('featured');
    });

    Route::prefix('career')->name('career.')->group(function () {
        Route::get('/', [CareerController::class, 'index'])->name('index');
        Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
        Route::post('/{careerId}/apply', [CareerController::class, 'apply'])
            ->whereNumber('careerId')
            ->middleware('throttle:job-application')
            ->name('apply');
    });

    Route::prefix('coverage')->name('coverage.')->group(function () {
        Route::get('/', [CoverageController::class, 'index'])->name('index');
        Route::post('/check', [CoverageController::class, 'check'])->middleware('throttle:coverage-check')->name('check');
    });

    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware(['throttle:contact-form', 'captcha'])
        ->name('contact.store');

    Route::prefix('network-status')->name('network-status.')->group(function () {
        Route::get('/', [NetworkStatusController::class, 'index'])->name('index');
        Route::get('/{nodeId}/bandwidth-chart', [NetworkStatusController::class, 'bandwidthChart'])->whereNumber('nodeId')->name('bandwidth-chart');
        Route::post('/report-trouble', [NetworkStatusController::class, 'reportTrouble'])->middleware('throttle:contact-form')->name('report-trouble');
    });
});
