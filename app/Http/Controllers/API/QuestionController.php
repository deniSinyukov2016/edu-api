<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Filters\ModelFilter;
use App\Http\Filters\ParentFilter;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Models\Question;
use App\Scopes\Search\SearchScope;

class QuestionController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Display questions list
     * @apiParam string $with in_query | Add records to questions
     * @apiParam integer $count in_query | Count display question, 10 by default
     * @apiParam integer $test_id in_query | Set filter by test_id field, null by default
     * @apiErr  401 | Unauthorized
     * @apiErr  403 | Unauthorized access
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function index()
    {
        $this->authorize('view', Question::class);

        return response()->json(ParentFilter::setModel(Question::class,request()));
    }

    /**
     * @param StoreQuestionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Add new question
     * @apiParam integer $type_answer_id in_query required| Question type_answer_id
     * @apiParam integer $test_id in_query required| Question test_id
     * @apiParam string  $text in_query required| Question text
     * @apiParam integer $count_correct in_query required| Question count_correct
     * @apiParam array $answers in_query required| Answers array (only title and is_correct fields)
     *
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 201 | Whatever message is send from backend on success created
     */
    public function store(StoreQuestionRequest $request)
    {
        /** @var Question $question */
        $question = Question::query()->create(array_except($request->validated(), 'answers'));
        $question->addAnswers($request->get('answers'));

        return response()->json($question->load('answers'), 201);
    }

    /**
     * @param Question $question
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Show question by id
     * @apiParam integer $question in_path required| Question id
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function show(Question $question)
    {
        $this->authorize('view', Question::class);

        return response()->json($question->load('answers'));
    }

    /**
     * @param UpdateQuestionRequest $request
     * @param Question $question
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Update current question
     * @apiParam integer $type_answer_id in_query | Question type_answer_id
     * @apiParam integer $test_id in_query | Question test_id
     * @apiParam string  $text in_query | Question text
     * @apiParam integer $count_correct in_query | Question count_correct
     * @apiParam array $answers in_query | Array answers ,who will update or create. If answer has id - it will be update. If not - will create. Answer must has 2 attribute - title and is_correct field
     *
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 200 | Whatever message is send from backend on success created
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->update(array_except($request->validated(), 'answers'));

        $this->updateAnswers($question, $request->get('answers'));

        return response()->json($question->load('answers'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @apiDesc Delete question by ids array
     * @apiParam array $id in_query required| Simple array questions id ([1, 2, 3 ...])
     * @apiErr  422 | Validation failed
     * @apiErr  403 | Unauthorized access
     * @apiErr  401 | Unauthorized .
     * @apiResp 204 | Whatever message is send from backend on success deleted
     */
    public function destroy()
    {
        $this->authorize('delete', Question::class);

        \request()->validate([
            'id'   => 'required|array',
            'id.*' => 'required|exists:questions,id'
        ]);

        return response()->json(Question::query()->whereIn('id', \request('id'))->delete(), 204);
    }

    /**
     * @param Question $question
     * @param array $answers
     */
    private function updateAnswers($question, $answers)
    {
        if (count($answers) > 0) {
            foreach ($answers as $answer) {
                $question->answers()->updateOrCreate(['id' => $answer['id'] ?? null], $answer);
            }
        }
    }
}
