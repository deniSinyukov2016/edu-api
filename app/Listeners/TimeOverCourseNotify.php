<?php

namespace App\Listeners;

use App\Events\CourseUserEvent\TimeOverCourse;
use App\Models\Event;
use App\Enum\EventEnum;

class TimeOverCourseNotify
{
    public function handle(TimeOverCourse $event)
    {
        foreach ($event->users as $user) {
            Event::query()->create([
                'user_id'       => $user,
                'course_id'     => $event->course->id,
                'event_type_id' => EventEnum::TIME_OVER_COURSE
            ]);
        }
    }
}
