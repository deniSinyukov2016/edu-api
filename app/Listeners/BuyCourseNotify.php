<?php

namespace App\Listeners;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\BuyCourse;
use App\Models\Event;

class BuyCourseNotify
{
    public function handle(BuyCourse $event)
    {
        foreach ($event->users as $user) {
            Event::query()->create([
                'user_id'       => $user,
                'course_id'     => $event->course->id,
                'event_type_id' => EventEnum::START_COURSE
            ]);
            $event->course->lessons()->each(function ($lesson) use ($user) {
                $lesson->lessonUser()->create([
                    'user_id'   => $user,
                ]);
            });
        }
    }
}
