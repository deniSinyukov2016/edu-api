<?php

namespace App\Queries;

use App\Models\Course;
use App\Models\User;
use App\Pivots\CourseUser;

class CourseUserQuery
{
    public static function acceptCourseUser(User $user, Course $course)
    {
        $query = CourseUser::getCourseUser($user, $course);

        if (!$query->exists()) return;

        $courseId = $query->pluck('course_id')->toArray();


        $course =  Course::query()->whereKey($courseId)->with([
            'courseUser' => function ($courseUser) use ($user) {
                $courseUser->where('user_id', $user->id)->with('courseStatus');
            },
        ])->with(['lessons.typeLessons', 'modules'])
          ->withCount('lessons')
          ->withCount(['lessonComplete' => function ($lesson) use ($user) {
              $lesson->whereHas('lessonUser', function ($lessonUser) use ($user) {
                  $lessonUser->where('user_id', $user->id);
              });
          }])
          ->withCount(['lessonUncomplete' => function ($lesson) use ($user) {
              $lesson->whereHas('lessonUser', function ($lessonUser) use ($user) {
                  $lessonUser->where('user_id', $user->id);
              });
          }])->first();

          $course->lessons->map(function ($lesson) use ($user) {
              $query = $lesson->lessonUser()->where('user_id', $user->id);
              $lessonUser = $query->exists() ? $query->first()->is_complete : 0;
              $lesson->status = $lessonUser;
          });

        return $course;
    }
}
