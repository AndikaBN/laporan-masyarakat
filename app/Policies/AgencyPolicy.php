<?php

namespace App\Policies;

use App\Models\Agency;
use App\Models\User;

class AgencyPolicy
{
    /**
     * Determine whether the user can view any agencies.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the agency.
     */
    public function view(User $user, Agency $agency): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create agencies.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the agency.
     */
    public function update(User $user, Agency $agency): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the agency.
     */
    public function delete(User $user, Agency $agency): bool
    {
        return $user->isSuperAdmin();
    }
}
