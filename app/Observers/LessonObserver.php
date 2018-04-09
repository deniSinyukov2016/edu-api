<?php

namespace App\Observers;

use App\Events\StoreLessonEvent;
use App\Models\Lesson;

class LessonObserver
{
    public function created(Lesson $lesson)
    {
        event(new StoreLessonEvent(request(), $lesson));
    }
}
