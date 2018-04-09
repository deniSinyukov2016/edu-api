<?php

namespace App\Observers;

use App\Events\UpdateCourseEvent;
use App\Models\Course;

class CourseObserver
{
    public function updated(Course $course)
    {
        event(new UpdateCourseEvent(request(), $course));
    }
}
