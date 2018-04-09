<?php

namespace App\Pivots;

use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property integer course_id
 * @property integer user_id
 * @property string time_start
 * @property string close_time
 * @property integer course_status_id
 * @property User user
 * @property Course course
 */
class CourseUser extends Pivot
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function courseStatus()
    {
        return $this->belongsTo(CourseStatus::class);
    }

    public function getCreatedAtColumn()
    {
        return 'created_at';
    }

    public function getUpdatedAtColumn()
    {
        return 'updated_at';
    }

    public static function getCourseUser(User $user, Course $course)
    {
        return static::where(function ($query) use ($course, $user) {
            $query->where('user_id', $user->id)->where('course_id', $course->id);
        });
    }
}
