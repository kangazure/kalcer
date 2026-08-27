<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        Horizon::auth(function ($request) {
            return Gate::check('manage-network', [$request->user()])
                || $request->user()?->hasRoleSlug('super_admin');
        });
    }

    /**
     * Register the Horizon gate.
     *
     * Hanya Super Admin yang boleh mengakses dashboard Horizon (monitoring
     * queue job email, notifikasi, dan network polling).
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user) {
            return $user->hasRoleSlug('super_admin');
        });
    }
}
