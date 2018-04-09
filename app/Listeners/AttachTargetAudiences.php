<?php

namespace App\Listeners;

use App\Listeners\Interfaces\IAttachFiles;

class AttachTargetAudiences
{
    public function handle(IAttachFiles $event)
    {
        if ($event->getRequest()->exists('target_audiences')) {
            $event->getEntity()->addTargets($event->getRequest()->get('target_audiences'));
        }
    }
}
