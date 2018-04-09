<?php

namespace App\Http\Controllers\API;

use App\Events\StoreCourseEvent;
use App\Events\UpdateCourseEvent;
use App\Http\Controllers\Controller;
use App\Http\Filters\CourseFilter;
use App\Http\Filters\ParentFilter;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use App\Scopes\Search\SearchScope;

class CourseController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException
     *
     * @apiDesc Display courses list
     * @apiParam integer $count in_query | Count display courses, 10 by default
     * @apiParam string $with in_query | Set with : 'lessons','category','modules','events','courseUser', 'sertificates', 'targetAudiences'. Delimiter ','
     * @apiParam string $where in_query | Set where : 'status', 'category_id'. Delimiter ','
     * @apiParam string $whereLike in_query | Set whereLike : 'title', 'body'. Delimiter ','
     * @apiParam string $withCount in_query | Set withCount : 'lessonsValues, usersValues'. Delimiter ','
     * @apiParam string $sort_by in_query | Set sort_by : 'status, title, lessons_values_count, users_values_count'.
     * @apiParam string $order_by in_query | Set order_by : 'desc, asc'.
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        return response()->json(CourseFilter::get(request()));
    }

    /**
     * @param StoreCourseRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Add new course
     * @apiParam string  $title in_query required| Course title
     * @apiParam string  $meta_keywords in_query required|Course meta_keywords
     * @apiParam string  $meta_description in_query required|Course meta_description
     * @apiParam string  $slug in_query required|Course slug
     * @apiParam string  $body in_query |Course body
     * @apiParam double  $price in_query required|Course price
     * @apiParam integer $duration in_query required|Course duration
     * @apiParam boolean $status in_query |Course status
     * @apiParam array $target_audiences in_query | Target audiences array
     * @apiParam integer $category_id in_query required|Course category_id
     * @apiParam file    $image in_query |Course image
     * @apiParam array   $target_audiences in_query |Target title []
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success created
     */
    public function store(StoreCourseRequest $request)
    {
        /** @var Course $course */
        $course = Course::query()->create(array_except($request->validated(), [
            'image',
            'is_sertificate',
            'files',
            'target_audiences'
        ]));

        event(new StoreCourseEvent($request, $course));

        return response()->json($course->load(['targetAudiences', 'images']), 201);
    }

    /**
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Show course by id
     * @apiParam integer $course in_path required| Course id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 404 | Not found
     * @apiResp 403 |Unauthorized access
     * @apiResp 401 | Unauthorized
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        return response()->json($course->load([
            'modules',
            'lessons.typeLessons',
            'images',
            'targetAudiences',
            'category',
            'sertificates'
        ]));
    }

    /**
     * @param UpdateCourseRequest $request
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc  Update  current course
     * @apiParam integer $course in_path required| Course id
     * @apiParam string  $title in_query | Course title
     * @apiParam string  $meta_keywords in_query | Course meta_keywords
     * @apiParam string  $meta_description in_query | Course meta_description
     * @apiParam string  $slug in_query | Course slug
     * @apiParam string  $body in_query | Course body
     * @apiParam double  $price in_query | Course price
     * @apiParam integer $duration in_query | Course duration
     * @apiParam array   $target_audiences in_query | Target audiences array
     * @apiParam boolean $status in_query | Course status
     * @apiParam integer $category_id in_query | Course category_id
     * @apiParam array   $target_audiences in_query |Target title []
     *
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update(array_except($request->validated(), ['target_audiences']));

        event(new UpdateCourseEvent($request, $course));

        return response()->json($course->load(['targetAudiences']));
    }

    /**
     * @param Course $course
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete course by id
     * @apiParam integer $course in_path required| Course id
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', Course::class);

        return response()->json($course->delete(), 204);
    }
}
