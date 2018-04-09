<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AttachUserRequest;
use App\Models\Lesson;
use App\Http\Controllers\Controller;

class LessonUserController extends Controller
{
    /**
     * @apiDesc Set complete lesson for users
     * @apiParam array $users in_query | Users array []
     * @apiParam integer $lesson in_path | Lesson id
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success
     *
     * @param AttachUserRequest $request
     * @param Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttachUserRequest $request, Lesson $lesson)
    {
        $lesson->setComplete($request->users);

        return response()->json([], 204);
    }
}
