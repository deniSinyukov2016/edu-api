<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Enum\PermissionEnum;
use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewAnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @var Answer $answer */
    protected $answer;
    /** @var User $user */
    protected $user;
    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->answer       = create(Answer::class);
        $this->user         = create(User::class);
        $this->permission   = PermissionEnum::VIEW_ANSWER;
    }

    /** @test */
    public function it_user_can_view_list_answers_if_has_permissions_and_want_pagination()
    {
        $this->user->givePermissionTo($this->permission);

        create(Answer::class, [], 10);

        $response = $this->signIn($this->user)
            ->getJson(route('answers.index', ['count' => 10]))
            ->assertStatus(200)
            ->json();

        $this->assertEquals(11, $response['total']);
    }

    /** @test */
    public function it_user_can_not_view_list_answers_if_has_not_permissions()
    {
        $this->signIn($this->user)
             ->getJson(route('answers.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_not_authorized()
    {
        $this->getJson(route('answers.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_list_answers_by_question()
    {
        $this->user->givePermissionTo($this->permission);
        /** @var Question $question  */
        $question = create(Question::class);

        /** @var Answer $answer  */
        $answers = create(Answer::class, ['question_id' => $question->id ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('answers.index', ['question_id' => $question->id]))
            ->assertStatus(200)
            ->assertSee($answers[0]->title)
            ->json();

        $this->assertCount(5, $response['data']);
    }


    /** @test */
    public function it_user_can_view_single_answer_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->getJson(route('answers.show', $this->answer))
             ->assertStatus(200)
             ->assertJson($this->answer->toArray());
    }

    /** @test */
    public function it_user_can_not_view_single_answer_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->getJson(route('answers.show', $this->answer))
             ->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_view_single_answer_if_not_authorized()
    {
        $this->getJson(route('answers.show', $this->answer))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_list_answers_by_part_title()
    {
        $this->user->givePermissionTo($this->permission);

        /** @var Answer $answer  */
        $answers = create(Answer::class, ['title' => 'This title in request '.str_random(10) ], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('answers.index', ['title' => 'This title in']))
            ->assertStatus(200)
            ->assertSee($answers[0]->title)
            ->json();

        $this->assertCount(5, $response['data']);
    }

    /** @test */
    public function it_can_view_list_answers_by_array_id()
    {
        $this->user->givePermissionTo($this->permission);

        /** @var Answer $answer  */
        $answers = create(Answer::class, [], 5);

        $response = $this->signIn($this->user)
            ->getJson(route('answers.index', ['id[0]' => $answers[0]->id, 'id[1]' => $answers[1]->id]))
            ->assertStatus(200)
            ->assertSee($answers[0]->title)
            ->json();

        $this->assertCount(2, $response['data']);
    }

    /** @test */
    public function it_can_view_all_answers_in_one_query()
    {
        $this->user->givePermissionTo($this->permission);
        create(Answer::class, [], 20);

        $response = $this->signIn($this->user)
            ->getJson(route('answers.index', ['count' => 'nolimit']))->json();

        $this->assertCount(21, $response);
    }
}
