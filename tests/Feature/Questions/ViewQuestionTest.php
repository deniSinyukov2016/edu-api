<?php

namespace Tests\Feature\Questions;

use App\Enum\PermissionEnum;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user  */
    protected $user;

    /** @var string  */
    protected $permission;

    /** @var Question $question  */
    protected $question;

    public function setUp()
    {
        parent::setUp();

        $this->user = create(User::class);
        $this->permission = PermissionEnum::VIEW_QUESTION;
        $this->question = create(Question::class, ['count_correct' => 1]);
    }

    /** @test */
    public function it_user_can_view_list_questions_if_has_permissions_and_want_pagination()
    {
        $this->user->givePermissionTo($this->permission);
        $this->assertCount(1, Question::query()->get());
        create(Question::class, [], 10);
        $this->assertCount(11, Question::query()->get());
        $response = $this->signIn($this->user)
                         ->getJson(route('questions.index', ['count' => 10]))
                         ->assertStatus(200)
                         ->json();
        $this->assertCount(10, $response['data']);
    }

    /** @test */
    public function it_user_can_view_all_list_questions_if_has_permissions()
    {
        $this->user->givePermissionTo($this->permission);
        $this->assertCount(1, Question::query()->get());
        create(Question::class, [], 10);
        $this->assertCount(11, Question::query()->get());
        $response = $this->signIn($this->user)
                         ->getJson(route('questions.index'))
                         ->assertStatus(200)
                         ->json();

        $this->assertEquals(11, $response['total']);
    }

    /** @test */
    public function it_user_can_not_view_list_questions_if_has_not_permissions()
    {
        $this->signIn($this->user)
             ->getJson(route('questions.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('questions.index'))->assertStatus(401);
    }

    /** @test */
    public function it_can_view_list_questions_by_test()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Test $module  */
        $test = create(Test::class);

        /** @var Question $questions */
        $questions = create(Question::class, ['test_id' => $test->id ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('questions.index', ['test_id' => $test->id]))
            ->assertStatus(200)
            ->assertSee($questions[0]->text)
            ->json();

        $this->assertCount(5, $response['data']);
    }


    /** @test */
    public function it_user_can_view_single_question_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->getJson(route('questions.show', $this->question))
             ->assertStatus(200)
             ->assertJson($this->question->toArray());
    }

    /** @test */
    public function it_user_can_not_view_single_question_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('questions.show', $this->question))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_view_single_question_if_not_authorized()
    {
        $this->getJson(route('questions.show', $this->question))
            ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_list_questions_by_array_id()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Question $questions  */
        $questions = create(Question::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('questions.index', ['id[0]' => $questions[0]->id, 'id[1]' => $questions[1]->id]))
            ->assertStatus(200)
            ->json();

        $this->assertCount(2, $response['data']);
    }

    /** @test */
    public function it_can_view_list_question_by_part_text()
    {
        $this->user->givePermissionTo($this->permission);

        create(Question::class, ['text' => 'This text in request '.str_random(10) ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('questions.index', ['text' => 'text in']))
            ->assertStatus(200)
            ->json();

        $this->assertCount(5, $response['data']);
    }

    /** @test */
    public function it_can_view_all_questions_in_one_query()
    {
        $this->user->givePermissionTo($this->permission);
        create(Question::class, [], 20);

        $response = $this->signIn($this->user)
            ->getJson(route('questions.index', ['count' => 'nolimit']))->json();

        $this->assertCount(21, $response);
    }
}
