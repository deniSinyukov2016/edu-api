<?php

namespace App\Events;

use App\Listeners\Interfaces\IAttachFiles;
use App\Models\Course;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class StoreCourseEvent implements IAttachFiles
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $request;
    protected $course;

    public function __construct(Request $request, Course $course)
    {
        $this->request = $request;
        $this->course  = $course;
    }

    /**
     * @return Course
     */
    public function getEntity()
    {
        return $this->course;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
