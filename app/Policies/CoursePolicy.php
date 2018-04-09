<?php

namespace App\Policies;

use App\Enum\PermissionEnum;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Course allowed only users who buy course
     *
     * @param User $user
     * @param Course $course
     *
     * @return bool
     */
    public function view(User $user, Course $course)
    {
        return ($course->courseUser()->where('user_id', $user->id)->exists() &&
                $user->hasPermissionTo(PermissionEnum::VIEW_COURSE)) ||
               $user->hasRole('admin');
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->hasPermissionTo(PermissionEnum::DELETE_COURSE);
    }
}
