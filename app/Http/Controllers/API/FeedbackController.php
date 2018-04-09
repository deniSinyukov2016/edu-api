<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\FeedbackRequest;
use App\Jobs\SendFeedbackEmail;
use App\Models\Feedback;
use App\Http\Controllers\Controller;
use App\Scopes\Search\SearchScope;

class FeedbackController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \InvalidArgumentException|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Display feedback list
     * @apiParam integer $count in_query | Count display users, 10 by default
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        $this->authorize('view', Feedback::class);

        return response()->json(Feedback::query()->paginate(request('count', 10)));
    }

    /**
     * @param FeedbackRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Add new feedback
     * @apiParam string $name in_query required| Feedback name
     * @apiParam string $email in_query required| Feedback email
     * @apiParam string $message in_query required| Feedback message
     * @apiErr 422 | Validation failed
     * @apiResp 201 | Whatever message is send from backend on success
     */
    public function store(FeedbackRequest $request)
    {
        $feedback = Feedback::query()->create($request->validated());
        $this->dispatch(new SendFeedbackEmail($feedback));
        return response()->json($feedback, 201);
    }

    /**
     * @param Feedback $feedback
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show feedback by id
     * @apiParam integer $feedback in_path required| Feedback id
     * @apiErr 404 | Feedback not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function show(Feedback $feedback)
    {
        $this->authorize('view', Feedback::class);

        return response()->json($feedback);
    }

    /**
     * @param Feedback $feedback
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception|\Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete feedback by id
     * @apiParam integer $feedback in_path required| Feedback id
     * @apiErr 404 | Feedback not found
     * @apiErr 403 | Unauthorized access
     * @apiErr 401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success
     */
    public function destroy(Feedback $feedback)
    {
        $this->authorize('delete', Feedback::class);

        return response()->json($feedback->delete(), 204);
    }
}
