<?php

namespace Tests\Unit;

use App\Models\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_question_do_not_added_if_type_answer_do_not_exist_throw_exception()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Question::class, ['type_answer_id' => null]);
    }

    /** @test */
    public function it_question_do_not_added_if_test_do_not_exist_throw_exception()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Question::class, ['test_id' => null]);
    }
}
