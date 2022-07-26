<?php

namespace App\Providers;

use App\Passport\src\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // This method will register the routes necessary to issue access tokens and revoke access tokens, clients, and personal access tokens
        Passport::routes();

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });


        // tokens experiation
        Passport::tokensExpireIn(now()->addDays(1));
    }
}
