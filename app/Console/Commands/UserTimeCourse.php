<?php

namespace App\Console\Commands;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\TimeOverCourse;
use App\Models\Event;
use App\Pivots\CourseUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserTimeCourse extends Command
{
    protected $signature = 'time:over';
    protected $description = 'Course time check';

    public function handle()
    {
        /** @var CourseUser $courseUsers */
        $courseUsers = CourseUser::query()->where('close_time', '<', Carbon::now())->get();

        $courseUsers->each(function (CourseUser $courseUser) {
            if (!Event::query()->where(function ($query) use ($courseUser) {
                $query->where('course_id', $courseUser->course->id)
                      ->where('user_id', $courseUser->user->id)
                      ->where('event_type_id', EventEnum::TIME_OVER_COURSE);
            })->exists()) {
                event(new TimeOverCourse($courseUser->course, [$courseUser->user->id]));
            }
        });
    }
}
