<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use App\Pivots\TestUser;
use App\Queries\QuestionQuery;
use Carbon\Carbon;

class TestUserController extends Controller
{
    /**
     * @apiDesc     Show question with answers for test
     * @apiParam    integer $test in_path required| Test id
     * @apiResp     200 | Whatever message is send from backend on success
     * @apiErr      401 | Unauthorized .
     *
     * @param       Test $test
     *
     * @return      \Illuminate\Http\JsonResponse
     */
    public function show(Test $test)
    {
        return response()->json($test->questionAnswers());
    }

    /**
     * @apiDesc     Start timer for test only one user
     * @apiParam    integer $test in_path required| Test id
     * @apiParam    integer $user in_path required| User id
     *
     * @param       Test $test
     * @param       User $user
     *
     * @return      \Illuminate\Http\JsonResponse
     * @throws      \Exception
     */
    public function store(Test $test, User $user)
    {
        return response()->json($test->startTest($user));
    }

    /**
     * @apiDesc     Show time left for test
     * @apiParam    integer $test in_path required| Test id
     * @apiParam    integer $user in_path required| User id
     *
     * @param       Test $test
     * @param       User $user
     *
     * @return      \Illuminate\Http\JsonResponse
     */
    public function time(Test $test, User $user)
    {
        $testUser = $test->getEndTime($user);

        if (!$testUser) {
            return response()->json(['not found']);
        }
        if (Carbon::now() >= Carbon::parse($testUser->end)) {
            return response()->json(gmdate('H:i:s', 0));
        }
        $time = Carbon::now()->diffInSeconds(Carbon::parse($testUser->end));

        $data = [
            'time_left' => gmdate('H:i:s', $time),
            'attemp'    => $test->count_attemps - $testUser->count_attemps,
            'success'   => $testUser->is_success
        ];

        return response()->json($data);
    }

    /**
     * @apiDesc     Destroy timer
     * @apiParam    integer $test in_path required| Test id
     * @apiParam    integer $user in_path required| User id
     *
     * @param       Test $test
     * @param       User $user
     *
     * @return      \Illuminate\Http\JsonResponse
     * @throws      \Exception
     */
    public function destroy(Test $test, User $user)
    {
        $query = TestUser::getTestUser($user, $test);
        if ($query->exists()) {
            $query->update([
                'end'   => Carbon::now(),
                'start' => Carbon::now(),
            ]);
        }

        return response()->json($query->get());
    }

    /**@apiDesc     Result
     * @apiParam    integer $test in_path required| Test id
     * @apiParam    integer $user in_path required| User id
     * @apiParam    integer $is_success in_query required| is_success 1 or 0
     *
     * @param       Test $test
     * @param       User $user
     *
     * @return int
     */
    public function testSuccess(Test $test, User $user)
    {
        $this->validate(request(), ['is_success' => 'required|boolean']);

        $query = TestUser::getTestUser($user, $test);

        !$query->exists() ?: $query->update(request()->only('is_success'));

        return response()->json([$query->first()]);
    }

    /**
     * @apiDesc     Check result test
     * @apiParam    integer $test in_path required| Test id
     * @apiParam    array $question in_query required| Sample: [question[id_question] => [id_answer, id_answer] ]
     * @param       Test $test
     * @return      \Illuminate\Http\JsonResponse
     */
    public function checkup(Test $test)
    {
        $this->validate(request(), [
            'question'   => 'required|array',
        ]);

        $request = request('question');

        $questions = $test->questions()->whereIn('id', array_keys($request));

        $questionsCorrect = QuestionQuery::questionSuccess($questions->get(), $request);

        $response = $questionsCorrect->count() >= $test->count_correct;

        if ($response) {
            return response()->json($questionsCorrect->load(['answers']));
        }

        return response()->json($questions->with(['answers' => function ($answer) {
            $answer->select(['title', 'question_id', 'id']);
        }])->get());
    }
}
