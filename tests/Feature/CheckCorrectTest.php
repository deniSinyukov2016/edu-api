<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use App\Models\TypeAnswer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckCorrectTest extends TestCase
{
    use RefreshDatabase;

    /** @var Test $test */
    protected $test;
    /** @var TypeAnswer $type_answer_multi */
    protected $type_answer_multi;
    /** @var TypeAnswer $type_answer_single */
    protected $type_answer_single;

    public function setUp()
    {
        parent::setUp();

        $this->test = create(Test::class, ['count_correct' => 1]);
        $this->type_answer_single = create(TypeAnswer::class, ['title' => 'Один ответ']);
        $this->type_answer_multi  = create(TypeAnswer::class, ['title' => 'Несколько ответов']);
    }

    /** @test */
    public function it_can_check_test_correct_test_success()
    {
        /** @var Question $question */
        $question = create(Question::class, [
            'test_id'        => $this->test->id,
            'count_correct'  => 2,
            'type_answer_id' => $this->type_answer_multi->id
        ]);

        $correct = $question->answers()->createMany(make(Answer::class, [
            'is_correct'     => true,
        ], 2)->toArray());

        $response = $this->signIn()
             ->postJson(route('tests.checkup.test', [
                'tests' => $this->test,
                "question[$question->id]" => $correct->pluck('id')->toJson()
             ]))
             ->assertStatus(200)
             ->json();

        $this->assertArrayHasKey('is_correct', $response[0]['answers'][0]);
    }

    /** @test */
    public function it_can_check_test_correct_test_fail()
    {
        /** @var Question $question */
        $question = create(Question::class, [
            'test_id'        => $this->test->id,
            'count_correct'  => 2,
            'type_answer_id' => $this->type_answer_multi->id
        ]);

        $incorrect = $question->answers()->createMany(make(Answer::class, [
            'is_correct'    => false
        ], 2)->toArray());


        $response = $this->signIn()
            ->postJson(route('tests.checkup.test', [
                'tests' => $this->test,
                "question[$question->id]" => $incorrect->pluck('id')->toJson()
            ]))
            ->assertStatus(200)
            ->json();

        $this->assertArrayNotHasKey('is_correct', $response[0]['answers'][0]);
    }

    /** @test */
    public function it_can_not_check_test_correct_if_incorrect_data()
    {
        $this->signIn()
             ->postJson(route('tests.checkup.test', $this->test))
             ->assertStatus(422);
    }

    /** @test */
    public function it_can_not_check_test_correct_if_not_auth()
    {
        $this->postJson(route('tests.checkup.test', $this->test))
             ->assertStatus(401);
    }
}
