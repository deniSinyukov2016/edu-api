<?php

namespace App\Providers;


use App\Events\BuyCourse;
use App\Events\FinishCourse;
use App\Events\NoMoreAttemptsTest;
use App\Events\StoreCourse;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\CourseUserEvent\BuyCourse'       => [
            'App\Listeners\BuyCourseNotify',
            'App\Listeners\LessonOpenNotify',
        ],
        'App\Events\CourseUserEvent\FinishCourse'    => [
            'App\Listeners\FinishCourseNotify',
        ],
        'App\Events\CourseUserEvent\TimeOverCourse'  => [
            'App\Listeners\TimeOverCourseNotify',
        ],
        'App\Events\CourseUserEvent\NoMoreAttemptsTest' => [
            'App\Listeners\NoMoreAttemptsNotify',
        ],
        'App\Events\StoreCourseEvent' => [
            'App\Listeners\AttachFiles',
            'App\Listeners\AttachImages',
            'App\Listeners\AttachTargetAudiences',
        ],
        'App\Events\StoreLessonEvent' => [
            'App\Listeners\AttachFiles',
            'App\Listeners\CreateFile',
            'App\Listeners\AddLessonNotify',
        ],
        'App\Events\UpdateCourseEvent' => [
            'App\Listeners\AttachTargetAudiences',
        ],
        'App\Events\UpdateLessonEvent' => [
            'App\Listeners\UpdateFiles',
        ],
        'App\Events\UpdateLessonEvent' => [
            'App\Listeners\UpdateFiles',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
