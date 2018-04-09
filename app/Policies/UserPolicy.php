<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_USERS);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::CREATE_USER);
    }

    public function delete(User $user, User $deletingUser)
    {
        return $user->hasPermissionTo(PermissionEnum::DELETE_USER) && $user->id !== $deletingUser->id;
    }

    public function edit(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::EDIT_USERS);
    }
}
