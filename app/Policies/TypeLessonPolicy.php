<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeLessonPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::VIEW_TYPE_LESSON);
    }
}
