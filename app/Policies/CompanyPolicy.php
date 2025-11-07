<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Company $company): bool
    {

        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'super_admin' || $user->role === 'staff';
    }

    public function update(User $user, Company $company): bool
    {
        return $user->role === 'super_admin' || $company->assigned_staff_id === $user->id;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->role === 'super_admin' || $company->assigned_staff_id === $user->id;
    }
}
