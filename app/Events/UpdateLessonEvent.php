<?php

namespace App\Events;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UpdateLessonEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $lesson;

    public function __construct(Request $request, Lesson $lesson)
    {
        $this->request = $request;
        $this->lesson  = $lesson;
    }
}
