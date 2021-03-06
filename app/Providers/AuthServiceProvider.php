<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        Gate::define('see-admin','App\Policies\UserPolicy@viewAdmin');
        Gate::define('see-trainers','App\Policies\UserPolicy@viewTrainer');
        Gate::define('see-trainees','App\Policies\UserPolicy@viewTrainee');
        Gate::define('access-admin','App\Policies\UserPolicy@accessAdmin');
        //
    }
}
