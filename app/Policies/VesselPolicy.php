<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vessel;

class VesselPolicy
{
    public function viewAny(User $user) { return true; }
    public function view(User $user, Vessel $vessel) { return true; }

    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role === 'super_admin';
    }

    public function update(User $user, Vessel $vessel)
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        return $user->name === $vessel->assigned_staff;
    }

    public function delete(User $user, Vessel $vessel)
    {
        if ($user->role === 'admin' || $user->role === 'super_admin') {
            return true;
        }

        return $user->name === $vessel->assigned_staff;
    }
}
