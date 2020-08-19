<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
//        Gate::define('admin-settings', function ($user) {
//            return $user->isAdmin();
//        });
//
//        Gate::define('user-policy', function ($user) {
//            if($user->isAdmin() == false) {
//                return true;
//            }
//            else {
//                return false;
//            }
//        });
    }
}
