<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa skema default integer length aman untuk PostgreSQL (tidak
        // wajib seperti MySQL, tapi tetap dipertahankan demi konsistensi).
        Schema::defaultStringLength(255);

        // Paksa seluruh URL yang digenerate Laravel pakai HTTPS di production
        // (penting di belakang load balancer/Cloudflare yang terminate TLS).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Gunakan Tailwind-friendly pagination view bawaan Laravel.
        Paginator::useTailwind();

        // Format default tanggal Carbon ke lokal Indonesia di seluruh app.
        \Carbon\Carbon::setLocale(config('app.locale', 'id'));
    }
}
