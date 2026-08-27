<?php

use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\CareerController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\CoverageController;
use App\Http\Controllers\Web\FaqController;
use App\Http\Controllers\Web\GalleryController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\NetworkStatusController;
use App\Http\Controllers\Web\NewsletterController;
use App\Http\Controllers\Web\PackageController;
use App\Http\Controllers\Web\PortfolioController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Controllers\Web\StaticPageController;
use App\Http\Controllers\Web\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Halaman Publik PT Jaringan Teknologi Sejahtera
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('tentang-kami')->name('about.')->group(function () {
    Route::get('/', [AboutController::class, 'index'])->name('index');
    Route::get('/visi-misi', [AboutController::class, 'vision'])->name('vision');
    Route::get('/sejarah', [AboutController::class, 'history'])->name('history');
});

Route::prefix('layanan')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
});

Route::get('/paket-internet', [PackageController::class, 'index'])->name('packages.index');

Route::prefix('coverage-area')->name('coverage.')->group(function () {
    Route::get('/', [CoverageController::class, 'index'])->name('index');
    Route::post('/cek-jangkauan', [CoverageController::class, 'check'])
        ->middleware(['throttle:coverage-check'])
        ->name('check');
});

Route::prefix('status-jaringan')->name('network-status.')->group(function () {
    Route::get('/', [NetworkStatusController::class, 'index'])->name('index');
    Route::get('/json', [NetworkStatusController::class, 'statusJson'])->name('json');
    Route::post('/lapor-gangguan', [NetworkStatusController::class, 'submitTroubleReport'])
        ->middleware(['throttle:contact-form'])
        ->name('report');
});

Route::prefix('portfolio')->name('portfolio.')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('index');
    Route::get('/{slug}', [PortfolioController::class, 'show'])->name('show');
});

Route::prefix('galeri')->name('gallery.')->group(function () {
    Route::get('/', [GalleryController::class, 'index'])->name('index');
    Route::get('/{slug}', [GalleryController::class, 'show'])->name('show');
});

Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonial.index');

Route::prefix('karir')->name('career.')->group(function () {
    Route::get('/', [CareerController::class, 'index'])->name('index');
    Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
    Route::post('/{careerId}/lamar', [CareerController::class, 'apply'])
        ->whereNumber('careerId')
        ->middleware(['throttle:job-application'])
        ->name('apply');
});

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::prefix('kontak')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/kirim', [ContactController::class, 'submit'])
        ->middleware(['throttle:contact-form', 'captcha'])
        ->name('submit');
});

Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::post('/subscribe', [NewsletterController::class, 'subscribe'])
        ->middleware(['throttle:newsletter'])
        ->name('subscribe');
    Route::get('/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('unsubscribe');
});

Route::get('/kebijakan-privasi', [StaticPageController::class, 'privacyPolicy'])->name('static.privacy');
Route::get('/syarat-ketentuan', [StaticPageController::class, 'terms'])->name('static.terms');

Route::get('/maintenance', function () {
    return view('errors.maintenance');
})->name('maintenance');
