<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Super Admin selalu lolos seluruh Gate check tanpa terkecuali.
        Gate::before(function (User $user, string $ability) {
            return $user->hasRoleSlug('super_admin') ? true : null;
        });

        Gate::define('access-admin-dashboard', function (User $user) {
            return $user->role && in_array($user->role->slug, [
                'super_admin', 'admin', 'editor', 'marketing', 'operator',
            ], true);
        });

        Gate::define('manage-users', fn (User $user) => $user->hasRoleSlug(['super_admin', 'admin']));
        Gate::define('manage-settings', fn (User $user) => $user->hasRoleSlug('super_admin'));
        Gate::define('manage-posts', fn (User $user) => $user->hasPermissionSlug('posts.create'));
        Gate::define('moderate-comments', fn (User $user) => $user->hasPermissionSlug('comments.moderate'));
        Gate::define('manage-network', fn (User $user) => $user->hasRoleSlug(['super_admin', 'admin', 'operator']));
        Gate::define('manage-marketing-content', fn (User $user) => $user->hasRoleSlug(['super_admin', 'admin', 'marketing']));
        Gate::define('view-analytics', fn (User $user) => $user->hasPermissionSlug('analytics.view'));
        Gate::define('manage-backup', fn (User $user) => $user->hasRoleSlug('super_admin'));
    }
}
