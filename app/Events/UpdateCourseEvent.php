<?php

namespace App\Events;

use App\Listeners\Interfaces\IAttachFiles;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UpdateCourseEvent implements IAttachFiles
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;
    protected $entity;

    public function __construct(Request $request, Course $entity)
    {
        $this->request = $request;
        $this->entity  = $entity;
    }

    /**
     * @return Course
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
