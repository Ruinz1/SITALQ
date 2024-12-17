<?php

namespace App\Policies;

use App\Models\User;
use App\Models\jadwal;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class JadwalPolicy
{
    use HandlesAuthorization;

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
