<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TargetAudience\StoreTargetAudienceRequest;
use App\Models\Course;
use App\Models\TargetAudience;

use App\Http\Controllers\Controller;

class TargetAudienceController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Target target list
     * @apiParam integer $count in_query | Count display users, 10 by default
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */

    public function index()
    {
        $this->authorize('view', TargetAudience::class);

        return response()->json(TargetAudience::query()->paginate(request('count', 10)));
    }

    /**
     * @param StoreTargetAudienceRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Store new target
     * @apiParam string $title  in_query required
     * @apiErr 422 | Validation failed
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success
     */

    public function store(StoreTargetAudienceRequest $request)
    {
        return response()->json(TargetAudience::query()->create($request->validated()), 201);
    }

    /**
     * @param TargetAudience $target
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show target by id
     * @apiParam integer $target in_path required| TargetAudience id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 403 | Unauthorized access
     * @apiResp 401 | Unauthorized .
     * @apiResp 404 | Not found .
     */
    public function show(TargetAudience $target)
    {
        $this->authorize('view', TargetAudience::class);

        return response()->json($target);
    }

    /**
     * @param TargetAudience $target
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete target by id
     * @apiParam integer $target in_path required| TargetAudience id
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function destroy(TargetAudience $target)
    {
        $this->authorize('delete', TargetAudience::class);

        if (request()->exists('course_id')) {
            $data = request()->get('course_id');

            Course::query()->find($data)->targetAudiences()->detach($target);
        }

        return response()->json($target->delete(), 204);
    }
}
