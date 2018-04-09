<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Test\StoreTestRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Models\Test;
use App\Scopes\Search\SearchScope;

class TestController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc  Display tests list
     * @apiParam integer $count in_query | Count display tests, 10 by default
     * @apiParam integer $lesson_id in_query |Set filter for display test on single lesson
     * @apiParam integer $count in_query | Count display tests, 10 by default (nolimit - all tests)
     * @apiResp  200 | Whatever message is send from backend on success
     * @apiResp  403 | Unauthorized access
     * @apiResp  401 | Unauthorized .
     */
    public function index()
    {
        $this->authorize('view', Test::class);

        if (request()->count == 'nolimit') {
            return response()->json(
                Test::withGlobalScope('search', new SearchScope(request()))->get()
            );
        }

        return response()->json(
            Test::withGlobalScope('search', new SearchScope(request()))->paginate(request('count', 10))
        );
    }


    /**
     * @param StoreTestRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Store new test
     * @apiParam string $time_passing in_query required| Test time_passing
     * @apiParam boolean $is_random in_query | Test is_random
     * @apiParam boolean $is_require in_query | Test is_require
     * @apiParam integer $count_attemps in_query required| Test count_attemps
     * @apiParam integer $count_correct in_query required| Test count_correct
     * @apiParam integer $lesson_id in_query required| Test lesson_id
     *
     * @apiErr 422 | Validation failed
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success
     */
    public function store(StoreTestRequest $request)
    {
        return response()->json(Test::query()->create($request->validated()), 201);
    }

    /**
     * @param int $test
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show test with lesson by id
     * @apiParam integer $test in_path required| Test id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 403 | Unauthorized access
     * @apiResp 401 | Unauthorized .
     * @apiResp 404 | Not found .
     */
    public function show(int $test)
    {
        $this->authorize('view', Test::class);

        return response()->json(Test::query()->where('id', $test)->with('lesson')->first());
    }


    /**
     * @param UpdateTestRequest $request
     * @param Test $test
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Update current test
     * @apiParam integer $test in_path required| Test id
     * @apiParam string $time_passing in_query | Test time_passing
     * @apiParam boolean $is_random in_query | Test is_random
     * @apiParam boolean $is_require in_query | Test is_require
     * @apiParam integer $count_attemps in_query | Test count_attemps
     * @apiParam integer $count_correct in_query | Test count_correct
     * @apiParam integer $lesson_id in_query | Test lesson_id
     *
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function update(UpdateTestRequest $request, Test $test)
    {
        $test->update($request->validated());

        return response()->json($test);
    }


    /**
     * @param Test $test
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete test by id
     * @apiParam integer $test in_path required| Test id
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function destroy(Test $test)
    {
        $this->authorize('delete', Test::class);

        return response()->json($test->delete(), 204);
    }
}
