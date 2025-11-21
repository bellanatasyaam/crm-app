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
        return true;
    }

    public function update(User $user, Company $company): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->role === 'admin' || $company->assigned_staff_id === $user->id;
    }
}