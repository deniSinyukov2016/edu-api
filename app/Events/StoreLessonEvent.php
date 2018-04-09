<?php

namespace App\Events;

use App\Listeners\Interfaces\IAttachFiles;
use App\Models\Lesson;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class StoreLessonEvent implements IAttachFiles
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;
    protected $entity;

    public function __construct(Request $request, Lesson $entity)
    {
        $this->request = $request;
        $this->entity  = $entity;
    }

    /**
     * @return Lesson
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
