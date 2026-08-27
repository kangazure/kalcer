<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CoverageAreaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NetworkMonitorController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TroubleReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
| Middleware 'role:slug1,slug2' mengarah ke App\Http\Middleware\EnsureUserHasRole,
| selaras dengan RLS policy di level database Supabase (defense in depth).
| Role tersedia: super_admin, admin, editor, marketing, operator.
*/

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('throttle:login')->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.submit');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // --- Konten Blog: Super Admin, Admin, Editor ---
        Route::middleware('role:super_admin,admin,editor')->group(function () {
            Route::resource('posts', PostController::class)->except(['show']);
            Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit'])->parameters(['categories' => 'category']);
            Route::resource('tags', TagController::class)->except(['show', 'create', 'edit'])->parameters(['tags' => 'tag']);

            Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
            Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
            Route::put('/faq/{faq}', [FaqController::class, 'update'])->name('faq.update');
            Route::delete('/faq/{faq}', [FaqController::class, 'destroy'])->name('faq.destroy');

            Route::prefix('comments')->name('comments.')->group(function () {
                Route::get('/', [CommentController::class, 'index'])->name('index');
                Route::patch('/{comment}/approve', [CommentController::class, 'approve'])->name('approve');
                Route::patch('/{comment}/reject', [CommentController::class, 'reject'])->name('reject');
                Route::patch('/{comment}/spam', [CommentController::class, 'markSpam'])->name('spam');
                Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
            });
        });

        // --- Layanan & Paket: Super Admin, Admin, Marketing ---
        Route::middleware('role:super_admin,admin,marketing')->group(function () {
            Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit'])->parameters(['services' => 'service']);
            Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
            Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');

            Route::resource('packages', PackageController::class)->except(['show', 'create', 'edit']);
            Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
            Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');

            Route::resource('portfolio', PortfolioController::class)->except(['show', 'create', 'edit']);
            Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
            Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');

            Route::resource('testimonials', TestimonialController::class)->except(['show', 'create', 'edit'])->parameters(['testimonials' => 'testimonial']);
            Route::resource('banners', BannerController::class)->except(['show', 'create', 'edit'])->parameters(['banners' => 'banner']);
            Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
            Route::get('/banners/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');

            Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
            Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
            Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
            Route::put('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.update');
            Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
            Route::post('/sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');

            Route::resource('popups', PopupController::class)->except(['show', 'create', 'edit'])->parameters(['popups' => 'popup']);
            Route::resource('gallery', GalleryController::class)->except(['show', 'create', 'edit']);
            Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
            Route::get('/gallery/{gallery}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');

            Route::prefix('subscribers')->name('subscribers.')->group(function () {
                Route::get('/', [SubscriberController::class, 'index'])->name('index');
                Route::get('/export', [SubscriberController::class, 'export'])->name('export');
                Route::delete('/{subscriber}', [SubscriberController::class, 'destroy'])->name('destroy');
            });

            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        });

        // --- Tim, Karir, Kontak, Halaman, Users: Super Admin, Admin ---
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::resource('team', TeamController::class)->except(['show', 'create', 'edit']);

            Route::prefix('career')->name('career.')->group(function () {
                Route::get('/', [CareerController::class, 'index'])->name('index');
                Route::post('/', [CareerController::class, 'store'])->name('store');
                Route::put('/{career}', [CareerController::class, 'update'])->name('update');
                Route::delete('/{career}', [CareerController::class, 'destroy'])->name('destroy');
                Route::get('/{career}/applications', [CareerController::class, 'applications'])->name('applications');
                Route::patch('/applications/{application}/status', [CareerController::class, 'updateApplicationStatus'])->name('applications.status');
            });

            Route::prefix('contact')->name('contact.')->group(function () {
                Route::get('/', [ContactController::class, 'index'])->name('index');
                Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
                Route::put('/{contact}', [ContactController::class, 'update'])->name('update');
                Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
            });

            Route::resource('pages', PageController::class)->except(['show', 'create', 'edit']);
            Route::resource('users', UserController::class)->except(['show']);
        });

        // --- Modul Jaringan/ISP: Super Admin, Admin, Operator ---
        Route::middleware('role:super_admin,admin,operator')->group(function () {
            Route::prefix('network-monitor')->name('network-monitor.')->group(function () {
                Route::get('/', [NetworkMonitorController::class, 'index'])->name('index');
                Route::post('/', [NetworkMonitorController::class, 'store'])->name('store');
                Route::get('/{networkMonitor}', [NetworkMonitorController::class, 'show'])->name('show');
                Route::get('/{networkMonitor}/chart-data', [NetworkMonitorController::class, 'chartData'])->name('chart-data');
                Route::put('/{networkMonitor}', [NetworkMonitorController::class, 'update'])->name('update');
                Route::delete('/{networkMonitor}', [NetworkMonitorController::class, 'destroy'])->name('destroy');
            });

            Route::resource('coverage-area', CoverageAreaController::class)->except(['show', 'create', 'edit']);
            Route::resource('maintenance', MaintenanceController::class)->except(['show', 'create', 'edit']);

            Route::prefix('trouble-report')->name('trouble-report.')->group(function () {
                Route::get('/', [TroubleReportController::class, 'index'])->name('index');
                Route::get('/{troubleReport}', [TroubleReportController::class, 'show'])->name('show');
                Route::put('/{troubleReport}', [TroubleReportController::class, 'update'])->name('update');
            });
        });

        // --- Media Manager: semua role staff aktif ---
        Route::middleware('role:super_admin,admin,editor,marketing,operator')->group(function () {
            Route::get('/media', [MediaController::class, 'index'])->name('media.index');
            Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
            Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
        });

        // --- Hanya Super Admin ---
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/settings/{group?}', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings/{group}', [SettingController::class, 'update'])->name('settings.update');

            Route::prefix('backup')->name('backup.')->group(function () {
                Route::get('/', [BackupController::class, 'index'])->name('index');
                Route::post('/run', [BackupController::class, 'run'])->name('run');
                Route::get('/download/{path}', [BackupController::class, 'download'])->where('path', '.*')->name('download');
            });
        });

        // --- Activity Log & Visitor: Super Admin, Admin ---
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
            Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
        });
    });
});
