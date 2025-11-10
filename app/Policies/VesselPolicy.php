<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vessel;

class VesselPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Vessel $vessel)
    {
        return true;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['staff', 'admin', 'super_admin']);
    }

    public function update(User $user, Vessel $vessel)
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return $vessel->assigned_staff_id === $user->id;
    }

    public function delete(User $user, Vessel $vessel)
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return $vessel->assigned_staff_id === $user->id;
    }
}
