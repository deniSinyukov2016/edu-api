<?php

namespace App\Listeners;

use App\Jobs\FilePodcast;
use App\Listeners\Interfaces\IAttachFiles;

class AttachFiles
{
    public function handle(IAttachFiles $event)
    {
        dispatch(new FilePodcast($event->getRequest(), $event->getEntity()));
    }
}
