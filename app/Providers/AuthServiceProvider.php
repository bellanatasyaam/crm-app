<?php

namespace App\Providers;

use App\Models\Company;
use App\Policies\CustomerPolicy;
use App\Models\Vessel;
use App\Policies\VesselPolicy;

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
        \App\Models\Company::class => \App\Policies\CustomerPolicy::class,
        \App\Models\Vessel::class => \App\Policies\VesselPolicy::class,
        \App\Models\Customer::class => \App\Policies\CustomerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Super admin boleh semua
        Gate::before(function ($user, $ability) {
            if ($user->role === 'super_admin') {
                return true;
            }
        });
    }
}
