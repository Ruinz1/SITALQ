<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole('Super_Admin','Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super_Admin');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('Admin')) {
            return $user->id === $model->id || $model->hasRole('Guru');
        }
        return $user->hasRole('Super_Admin');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Super_Admin');
    }
}
