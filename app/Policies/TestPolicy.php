<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_TEST);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::DELETE_TEST);
    }
}
