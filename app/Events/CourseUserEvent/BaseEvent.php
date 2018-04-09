<?php

namespace App\Events\CourseUserEvent;

use App\Models\Course;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

abstract class BaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Course $course */
    public $course;
    /** @var array $users */
    public $users;

    /**
     * Finish course
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
