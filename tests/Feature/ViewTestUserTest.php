<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTestUserTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function it_can_view_question_for_test_with_answers()
    {
        /** @var Test $test */
        $test = create(Test::class);

        /** @var Question $questions */
        $questions = create(Question::class, [
            'test_id'   => $test->id
        ], 5);
        $questions->each(function (Question $question) {
            $question->answers()->create(['title' => 'entotle', 'is_correct' => true]);
        });

        $response = $this->signIn()
             ->getJson(route('tests.show.test.all', $test))
             ->assertStatus(200)
             ->json();

        $this->assertCount(5, $response);
        $this->assertCount(1, $response[0]['answers']);
    }

    /** @test */
    public function it_can_view_question_for_test_with_answers_if_not_auth()
    {
        $this->getJson(route('tests.show.test.all', create(Test::class)))
             ->assertStatus(401);
    }
}
