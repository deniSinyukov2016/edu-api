<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_QUESTION);
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::DELETE_QUESTION);
    }
}
