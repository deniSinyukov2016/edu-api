<?php

namespace App\Listeners;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\FinishCourse;
use App\Models\Event;

class FinishCourseNotify
{
    public function handle(FinishCourse $event)
    {
        foreach ($event->users as $user) {
            Event::query()->create([
                'user_id'       => $user,
                'course_id'     => $event->course->id,
                'event_type_id' => EventEnum::FINISH_COURSE
            ]);
        }
    }
}
