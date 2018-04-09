<?php

namespace App\Listeners;

use App\Events\StoreLessonEvent;

class CreateFile
{
    public function handle(StoreLessonEvent $event)
    {
        if (request()->exists('file')) {
            $event->getEntity()->files()->create(['file' => request('file')]);
        }
    }
}
