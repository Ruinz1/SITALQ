<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class KelasPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin', 'Guru']);
    }

    public function view(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin', 'Guru']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin', 'Guru']);
    }

    public function update(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin', 'Guru']);
    }

    public function delete(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }
}
