<?php

namespace App\Policies;

use App\Models\Kas;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class KasPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }

    public function view(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }

    public function update(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }

    public function delete(User $user): bool
    {
        return $user->hasRole(['Super_Admin', 'Admin']);
    }
}
