<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Basic RBAC gates based on Admin.role
        Gate::define('view-admin', function ($user) {
            return in_array($user->role ?? null, ['super_admin','admin','editor','viewer']);
        });

        Gate::define('manage-mails', function ($user) {
            return in_array($user->role ?? null, ['super_admin','admin']);
        });

        Gate::define('manage-content', function ($user) {
            return in_array($user->role ?? null, ['super_admin','admin','editor']);
        });

        Gate::define('manage-members', function ($user) {
            return in_array($user->role ?? null, ['super_admin','admin']);
        });

        // 管理者ユーザーの管理はスーパ管理者のみ
        Gate::define('manage-admins', function ($user) {
            return in_array($user->role ?? null, ['super_admin']);
        });
    }
}
