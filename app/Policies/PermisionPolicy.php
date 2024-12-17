<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermisionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super_Admin');
    }

    public function view(User $user): bool
    {
        return $user->hasRole('Super_Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super_Admin');
    }

    public function update(User $user): bool
    {
        return $user->hasRole('Super_Admin');
    }

    public function delete(User $user): bool
    {
        return $user->hasRole('Super_Admin');
    }
}
