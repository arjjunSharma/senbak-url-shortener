<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...
    ];

    public function boot(): void
    {
        // Who can create short urls?
        Gate::define('create-short-url', function (User $user) {
            if ($user->isSuperAdmin() || $user->isAdmin() || $user->isMember()) {
                return false;
            }
            return $user->isSales() || $user->isManager();
        });

        // Can user see short url index?
        Gate::define('view-short-url-index', function (User $user) {
            // Allow everyone except SuperAdmin to view the short URL index.
            return strtolower((string) $user->role) !== User::ROLE_SUPER_ADMIN;
        });

        // Who can invite users (logic refined in controller)
        Gate::define('invite-user', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdmin();
        });
    }
}
