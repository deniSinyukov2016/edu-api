<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TypeLesson\StoreTypeLessonRequest;
use App\Models\TypeLesson;
use App\Http\Controllers\Controller;

class TypeLessonController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Display type lessons list
     * @apiParam integer $count in_query | Count display question, 10 by default
     * @apiErr  401 | Unauthorized
     * @apiErr  403 | Unauthorized access
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        $this->authorize('view', TypeLesson::class);

        return response()->json(TypeLesson::query()->paginate(request('count', 10)));
    }

    /**
     * @param StoreTypeLessonRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Store new type lesson
     * @apiParam string $title in_query required| TypeLesson title
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success
     */
    public function store(StoreTypeLessonRequest $request)
    {
        return response()->json(TypeLesson::query()->create($request->validated()), 201);
    }
}
