<?php

namespace App\Http\Controllers\API;

use App\Http\Filters\ParentFilter;
use App\Http\Requests\Answer\StoreAnswerRequest;
use App\Http\Requests\Answer\UpdateAnswerRequest;
use App\Scopes\Search\SearchScope;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Http\Controllers\Controller;

class AnswerController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException|\InvalidArgumentException
     *
     * @apiDesc Display answers list
     * @apiParam integer $count in_query | Count display answers, all by default
     * @apiParam integer $question_id in_query | Set filter for display answers on single question
     * @apiParam integer $count in_query | Count display answers, 10 by default (nolimit - all answers)
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        $this->authorize('view', Answer::class);

        return response()->json(ParentFilter::setModel(Answer::class, request()));
    }
    /**
     * @param StoreAnswerRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Add new answer
     * @apiParam string  $title in_query required| Answer title
     * @apiParam integer $question_id in_query required| Answer question_id
     * @apiParam integer $is_correct in_query |Answer is_correct
     *
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success created
     */
    public function store(StoreAnswerRequest $request)
    {
        return response()->json(Answer::query()->create($request->validated()), 201);
    }

    /**
     * @param Answer $answer
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show answer by id
     * @apiParam integer $answer in_path required| Answer id
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 404 | Not found
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     */
    public function show(Answer $answer)
    {
        $this->authorize('view', Answer::class);

        return response()->json($answer);
    }


    /**
     * @param UpdateAnswerRequest $request
     * @param Answer $answer
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Update current answer
     * @apiParam string  $title in_query | Answer title
     * @apiParam integer $question_id in_query | Answer question_id
     * @apiParam integer $is_correct in_query required| Answer is_correct
     *
     * @apiErr 422 | Validation failed
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function update(UpdateAnswerRequest $request, Answer $answer)
    {
        $answer->update($request->validated());

        return response()->json($answer);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete answers by answers id
     * @apiParam array $id in_query required| Simple answers ids ([1,3,6,9])
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function destroy()
    {
        $this->authorize('delete', Answer::class);

        \request()->validate([
            'id'   => 'required|array',
            'id.*' => 'required|exists:answers,id'
        ]);

        return response()->json(Answer::query()->whereIn('id', \request('id'))->delete(), 204);
    }
}
