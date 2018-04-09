<?php

namespace App\Listeners;

use App\Events\StoreCourseEvent;
use App\Jobs\ImagePodcast;

class AttachImages
{
    public function handle(StoreCourseEvent $event)
    {
        dispatch(new ImagePodcast($event->getRequest(), $event->getEntity()));
    }
}
