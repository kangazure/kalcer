<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\LogActivityMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\TrackVisitor;
use App\Http\Middleware\VerifyCaptcha;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Route groups tambahan yang dipisah untuk kejelasan modul.
            Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/blog.php'));

            Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('webhooks')
                ->group(base_path('routes/webhooks.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global yang berjalan di SETIAP request.
        $middleware->append(SecurityHeaders::class);
        $middleware->append(TrackVisitor::class);

        // Grup 'web': stack standar Laravel + security tambahan.
        $middleware->web(append: [
            LogActivityMiddleware::class,
        ]);

        // Grup 'api': stateless, ditambah throttle default.
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // Alias middleware kustom untuk dipakai di route definitions.
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'captcha' => VerifyCaptcha::class,
            'log.activity' => LogActivityMiddleware::class,
        ]);

        // Trust proxy headers (wajib di belakang Nginx/Cloudflare).
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR
            | Request::HEADER_X_FORWARDED_HOST
            | Request::HEADER_X_FORWARDED_PORT
            | Request::HEADER_X_FORWARDED_PROTO);

        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Render kustom untuk 404 — tampilkan halaman Blade branded JTS.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource tidak ditemukan.',
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        // Render kustom untuk 503 (maintenance mode dengan halaman branded).
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException $e, Request $request) {
            if (! $request->is('api/*')) {
                return response()->view('errors.maintenance', [], 503);
            }
        });

        // Laporkan exception penting ke channel khusus (security/network).
        $exceptions->reportable(function (\Throwable $e) {
            if ($e instanceof \App\Exceptions\NetworkMonitorException) {
                \Illuminate\Support\Facades\Log::channel('network_monitor')->error($e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });
    })
    ->create();
