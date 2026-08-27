<?php

namespace App\Providers;

use App\Services\Supabase\SupabaseAuthService;
use App\Services\Supabase\SupabaseClient;
use App\Services\Supabase\SupabaseStorageService;
use Illuminate\Support\ServiceProvider;

class SupabaseServiceProvider extends ServiceProvider
{
    /**
     * Register Supabase client & sub-service sebagai singleton agar koneksi
     * HTTP client (Guzzle) tidak dibangun ulang setiap kali di-resolve.
     */
    public function register(): void
    {
        $this->app->singleton(SupabaseClient::class, function ($app) {
            return new SupabaseClient(
                baseUrl: config('supabase.url'),
                anonKey: config('supabase.anon_key'),
                serviceRoleKey: config('supabase.service_role_key'),
                timeout: config('supabase.http.timeout', 15),
            );
        });

        $this->app->singleton(SupabaseStorageService::class, function ($app) {
            return new SupabaseStorageService($app->make(SupabaseClient::class));
        });

        $this->app->singleton(SupabaseAuthService::class, function ($app) {
            return new SupabaseAuthService($app->make(SupabaseClient::class));
        });

        $this->app->alias(SupabaseClient::class, 'supabase');
    }

    public function boot(): void
    {
        //
    }
}
