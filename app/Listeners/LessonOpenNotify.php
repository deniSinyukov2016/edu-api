<?php

namespace App\Listeners;

use App\Events\CourseUserEvent\BuyCourse;
use App\Models\Lesson;
use App\Pivots\LessonUser;

class LessonOpenNotify
{
    public function handle(BuyCourse $event)
    {
        if (! $event->course->hasLesson()) {
            return;
        }
        /** @var Lesson $lesson */
        $lesson = $event->course->lessons()->orderBy('created_at')->first();

        LessonUser::query()->where('lesson_id', $lesson->id)
            ->whereIn('user_id', $event->users)
            ->update(['is_close' => false]);
    }
}
