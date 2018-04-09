<?php

namespace App\Listeners;

use App\Events\StoreLessonEvent;
use App\Models\Lesson;
use App\Pivots\LessonUser;
use Illuminate\Database\Eloquent\Builder;

class AddLessonNotify
{
    // TODO is this need?
    public function handle(StoreLessonEvent $event)
    {
//        /** @var Lesson $lesson */
//        $lesson = $event->getEntity();
//
//        $userIds = $event->getEntity()->course->courseUser()->exists();
//        dd($userIds);
//        foreach ($userIds as $idUser) {
//
//            $lesson->lessonUser()->create([
//                'user_id'     => $idUser,
//                'is_close'    => true,
//                'is_complete' => false
//            ]);
//        }
    }
}
