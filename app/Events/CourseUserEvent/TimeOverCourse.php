<?php

namespace App\Events\CourseUserEvent;

use App\Models\Course;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TimeOverCourse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Course $course */
    public $course;
    /** @var array $users */
    public $users;

    /**
     * Time is over course
     *
     * @param Course $course
     * @param array $users
     */
    public function __construct(Course $course, array $users)
    {
        $this->course = $course;
        $this->users  = $users;
    }
}
