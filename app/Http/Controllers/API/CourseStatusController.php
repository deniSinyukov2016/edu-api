<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;

class CourseStatusController extends Controller
{
    /**
     * @apiDesc     Set status success finish for courseС
     * @apiParam    integer  $course in_path required| Course id
     * @apiParam    array    $user_id in_query required| Array ids users []
     *
     * @apiErr      422 | Validation failed
     * @apiErr      401 | Unauthorized .
     * @apiResp     204 | Success updated users
     *
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Course $course)
    {
        $this->validate(request(), ['user_id' => 'required|array|exists:users,id']);

        $userIds = User::query()->whereIn('id', request('user_id'))->pluck('id')->toArray();

        $course->setSuccess($userIds);

        return response()->json([], 204);
    }
}
