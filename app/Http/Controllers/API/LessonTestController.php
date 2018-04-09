<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use App\Models\Lesson;
use Exception;
use App\Http\Controllers\Controller;

class LessonTestController extends Controller
{
    /**
     * @param Course $course
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function store(Course $course, Lesson $lesson)
    {
        $lesson->setTestStatus($course, true);

        return response()->json([], 204);
    }
}
