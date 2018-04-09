<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::DELETE_CATEGORY);
    }
}
