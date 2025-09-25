<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class CustomerPolicy
{
    public function update(User $user, Customer $customer)
    {
        // Admin bisa update semua
        if ($user->isAdmin()) {
            return true;
        }

        // Staff hanya bisa update customer mereka sendiri
        return $customer->assigned_staff === $user->name;
    }

    public function delete(User $user, Customer $customer)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $customer->assigned_staff === $user->name;
    }
}
