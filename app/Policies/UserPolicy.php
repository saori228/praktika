<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view organizer panel.
     */
    public function viewOrganizer(User $user): bool
    {
        return $user->isAdmin() || $user->isOrganizer();
    }
}