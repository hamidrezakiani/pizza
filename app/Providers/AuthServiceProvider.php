<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Lib\ActionAuthorization;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
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
        Gate::define('index-category', function (User $user) {
            return ActionAuthorization::check($user, 1);
        });

        Gate::define('create-category', function (User $user) {
            return ActionAuthorization::check($user,2);
        });

        Gate::define('update-category', function (User $user) {
            return ActionAuthorization::check($user, 3);
        });

        Gate::define('delete-category', function (User $user) {

            return ActionAuthorization::check($user, 4);
        });

        Gate::define('show-category', function (User $user) {
            return ActionAuthorization::check($user, 5);
        });

        Gate::define('index-user', function (User $user) {
            return ActionAuthorization::check($user, 6);
        });

        Gate::define('create-user', function (User $user) {
            return ActionAuthorization::check($user, 7);
        });

        Gate::define('update-user', function (User $user) {
            return ActionAuthorization::check($user, 8);
        });

        Gate::define('delete-user', function (User $user) {

            return ActionAuthorization::check($user, 9);
        });

        Gate::define('show-user', function (User $user) {
            return ActionAuthorization::check($user, 10);
        });

        Gate::define('roles', function (User $user) {
            return ActionAuthorization::check($user, 11);
        });
    }
}
