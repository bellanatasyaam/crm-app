<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Company;
use App\Policies\CompanyPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Company::class => CompanyPolicy::class,
        \App\Models\Vessel::class => \App\Policies\VesselPolicy::class,
        \App\Models\Customer::class => \App\Policies\CustomerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // 💡 Letakkan BEFORE policy check lainnya
        Gate::before(function (User $user, $ability) {
            if ($user->role === 'super_admin') {
                return true;
            }
        });

        Gate::define('isAdmin', function (User $user) {
            return in_array($user->role, ['admin', 'super_admin']);
        });
    }
}
