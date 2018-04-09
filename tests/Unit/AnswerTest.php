<?php

namespace Tests\Unit;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_answer_do_not_added_if_question_not_exist()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Answer::class, ['question_id' => null]);
    }
}
