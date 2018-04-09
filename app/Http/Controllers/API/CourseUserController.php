<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CourseAcceptException;
use App\Http\Requests\AttachUserRequest;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use App\Pivots\CourseUser;
use App\Queries\CourseUserQuery;

class CourseUserController extends Controller
{
    /**
     * @param AttachUserRequest $request
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\CourseAcceptException
     * @apiDesc Accept course by id
     * @apiParam integer $course in_path required| Course id
     * @apiParam array $users in_query | Users ids []
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function store(AttachUserRequest $request, Course $course)
    {
        $data = $request->validated();

        $course->attachUsers($data['users']);

        return response()->json([], 204);
    }

    /**
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Show accepted users for course
     * @apiParam integer $course in_path required| Course id
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function users(Course $course)
    {
        return response()->json($course->acceptorsUser());
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Show accepted courses for user
     * @apiParam integer $user in_path required| User id
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function courses(User $user)
    {
        return response()->json($user->acceptorsCourse());
    }

    /**
     * @apiDesc Delete users for course
     * @apiParam integer $course in_path required| Course id
     * @apiParam array $users in_query | Users ids []
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     * @apiResp 204 | Whatever message is send from backend on success
     *
     * @param AttachUserRequest $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AttachUserRequest $request, Course $course)
    {
        $data = $request->validated();
        /** Delete course for users */
        $course->courseUser()->whereIn('user_id', $data['users'])->delete();

        /** Delete all lesson for users */
        $course->lessons->each(function (Lesson $lesson) use ($data) {
            $lesson->lessonUser()->whereIn('user_id', $data['users'])->delete();
        });

        return response()->json([], 204);
    }

    /**
     * @apiDesc Show single course for single user
     * @apiParam integer $course in_path required| Course id
     * @apiParam integer $user in_path required| User id
     * @apiErr  401 | Unauthorized
     * @apiErr  404 | Not found
     * @apiResp 200 | Whatever message is send from backend on success
     * @param User $user
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user, Course $course)
    {
        return response()->json(CourseUserQuery::acceptCourseUser($user, $course));
    }
}
