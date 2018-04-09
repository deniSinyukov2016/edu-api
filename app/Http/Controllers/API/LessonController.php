<?php

namespace App\Http\Controllers\API;

use App\Events\StoreLessonEvent;
use App\Events\UpdateLessonEvent;
use App\Http\Filters\ParentFilter;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Scopes\Search\SearchScope;

class LessonController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Display lessons list
     * @apiParam integer $count in_query | Count display lessons, 10 by default
     * @apiParam integer $module_id in_query | Set filter by module_id field, null by default
     * @apiParam integer $course_id in_query | Set filter by course_id field, null by default
     * @apiParam integer $count in_query | Count display lessons, 10 by default (nolimit - all lessons)
     * @apiParam string $with in_query | With fields ['files', 'course', 'test', 'module', 'typelessons']
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 403 | Unauthorized access
     * @apiResp 401 | Unauthorized .
     */
    public function index()
    {
        $this->authorize('view', Lesson::class);

        return response()->json(ParentFilter::setModel(Lesson::class,request()));
    }

    /**
     * @param StoreLessonRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Store new lesson
     * @apiParam string $name in_query required| Lesson name
     * @apiParam string $description in_query | Lesson description
     * @apiParam string $file in_query | Lesson file
     * @apiParam integer $type_lessons_id in_query required| Lesson type_lessons_id
     * @apiParam integer $module_id in_query | Lesson module_id
     * @apiParam integer $course_id in_query required| Lesson course_id
     * @apiErr 422 | Validation failed
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success
     */
    public function store(StoreLessonRequest $request)
    {
        /** @var Lesson $lesson */
        $lesson = Lesson::query()->create(
            array_except($request->validated(), ['file', 'is_sertificate', 'files'])
        );
        event(new StoreLessonEvent($request, $lesson));

        return response()->json($lesson, 201);
    }

    /**
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show lesson by id
     * @apiParam integer $lesson in_path required| Lesson id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 403 | Unauthorized access
     * @apiResp 401 | Unauthorized .
     * @apiResp 404 | Not found .
     */
    public function show(Lesson $lesson)
    {
        $this->authorize('view', Lesson::class);

        return response()->json($lesson->load('test', 'files', 'typelessons'));
    }

    /**
     * @param UpdateLessonRequest $request
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Update current lesson
     * @apiParam integer $lesson in_path required| Lesson id
     * @apiParam string $name in_query | Lesson name
     * @apiParam string $description in_query | Lesson description
     * @apiParam integer $type_lessons_id in_query | Lesson type_lessons_id
     * @apiParam integer $test_id in_query | Lesson test_id
     * @apiParam integer $module_id in_query | Lesson module_id
     * @apiParam integer $course_id in_query | Lesson course_id
     * @apiParam array $file in_query | Lesson file [id_file]
     * @apiErr 422 | Validation failed
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $lesson->update(array_except($request->validated(), 'file'));

        event(new UpdateLessonEvent($request, $lesson));

        return response()->json($lesson);
    }

    /**
     * @param Lesson $lesson
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete lesson by id
     * @apiParam integer $lesson in_path required| Lesson id
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function destroy(Lesson $lesson)
    {
        $this->authorize('delete', Lesson::class);

        return response()->json($lesson->delete(), 204);
    }
}
