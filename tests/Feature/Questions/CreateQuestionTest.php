<?php

namespace Tests\Feature\Questions;

use App\Enum\PermissionEnum;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @var array $question  */
    protected $question;

    /** @var string  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->question = make(Question::class)->toArray();
        $this->permission = PermissionEnum::CREATE_QUESTION;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_create_question_with_answers_if_has_permissions()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->givePermissionTo(PermissionEnum::CREATE_ANSWER);

        $this->question['answers'][0] = make(Answer::class)->toArray();
        $this->question['answers'][1] = make(Answer::class)->toArray();
        $this->question['answers'][2] = make(Answer::class)->toArray();

        $response = $this->signIn($this->user)
             ->postJson(route('questions.store'), $this->question)
             ->assertStatus(201)
             ->assertSee($this->question['text'])
             ->assertSee($this->question['answers'][0]['title'])
             ->assertSee($this->question['answers'][1]['title'])
             ->assertSee($this->question['answers'][2]['title'])
             ->json();

        $this->assertTrue(Question::query()->whereKey($response['id'])->exists());
        $this->assertEquals(3, Answer::query()->where('question_id', $response['id'])->count());
    }

    /** @test */
    public function it_user_can_not_create_question_without_one_answer()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->givePermissionTo(PermissionEnum::CREATE_ANSWER);

        $this->signIn($this->user)
             ->postJson(route('questions.store'), $this->question)
             ->assertStatus(422);
    }

    /** @test */
    public function it_user_can_not_create_question_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('questions.store'), $this->question)
             ->assertStatus(403);

        $this->assertDatabaseMissing('questions', $this->question);
    }

    /** @test */
    public function it_user_can_not_create_question_if_has_not_permission_to_create_answer()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('questions.store'), $this->question)
             ->assertStatus(403);

        $this->assertDatabaseMissing('questions', $this->question);
    }

    /** @test */
    public function it_user_can_not_add_question_if_not_authorize()
    {
        $this->postJson(route('questions.store'))
             ->assertStatus(401);
    }
}
