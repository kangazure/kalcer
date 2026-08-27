<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(config('security.throttle.api'))
                ->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(config('security.throttle.login'))
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu banyak percobaan login. Silakan coba lagi beberapa saat.',
                    ], 429);
                });
        });

        RateLimiter::for('contact-form', function (Request $request) {
            return Limit::perMinute(config('security.throttle.contact_form'))->by($request->ip());
        });

        RateLimiter::for('comment', function (Request $request) {
            return Limit::perMinute(config('security.throttle.comment'))->by($request->ip());
        });

        RateLimiter::for('coverage-check', function (Request $request) {
            return Limit::perMinute(config('security.throttle.coverage_check'))->by($request->ip());
        });

        RateLimiter::for('job-application', function (Request $request) {
            return Limit::perMinute(config('security.throttle.job_application'))->by($request->ip());
        });

        RateLimiter::for('newsletter', function (Request $request) {
            return Limit::perMinute(config('security.throttle.newsletter'))->by($request->ip());
        });
    }
}
