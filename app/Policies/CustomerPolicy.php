<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class CustomerPolicy
{
    public function viewAny(User $user)
    {
        return true; // semua user boleh lihat daftar customer
    }

    public function view(User $user, Customer $customer)
    {
        return true; // semua user boleh lihat detail
    }

    public function create(User $user)
    {
        // hanya admin & super_admin boleh create
        return in_array($user->role, ['staff', 'admin', 'super_admin']);
    }

    public function update(User $user, Customer $customer)
    {
        // admin/super_admin bebas
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        // staff cuma bisa update customer yang assigned ke dia (berdasarkan ID)
        return $user->id === $customer->assigned_staff_id;
    }

    public function delete(User $user, Customer $customer)
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        return $user->id === $customer->assigned_staff_id;
    }

}
