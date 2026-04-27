<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Book;
use App\Models\User;
use App\Policies\BookPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Book::class => BookPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate để kiểm tra role admin
        Gate::define('admin-access', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate để kiểm tra role librarian hoặc admin
        Gate::define('librarian-access', function (User $user) {
            return in_array($user->role, ['admin', 'librarian']);
        });
    }
}
