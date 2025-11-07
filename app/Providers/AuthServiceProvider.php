<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\Vessel::class => \App\Policies\VesselPolicy::class,
        \App\Models\Customer::class => \App\Policies\CustomerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });

        /**
         * ✅ Super admin boleh semua hal (akses penuh)
         */
        Gate::before(function (User $user, $ability) {
            if ($user->role === 'super_admin') {
                return true;
            }
        });
    }
}
