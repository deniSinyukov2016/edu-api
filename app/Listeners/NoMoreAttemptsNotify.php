<?php

namespace App\Listeners;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\NoMoreAttemptsTest;
use App\Models\Event;

class NoMoreAttemptsNotify
{
    public function handle(NoMoreAttemptsTest $event)
    {
        foreach ($event->users as $user) {
            Event::query()->create([
                'user_id'       => $user,
                'course_id'     => $event->course->id,
                'event_type_id' => EventEnum::ATTEMPTS_TEST_OUT
            ]);
        }
    }
}
