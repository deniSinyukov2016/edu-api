<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeedbackPolicy
{
    use HandlesAuthorization;

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::DELETE_FEEDBACK);
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_FEEDBACK);
    }
}
