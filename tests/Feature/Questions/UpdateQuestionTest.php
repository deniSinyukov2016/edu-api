<?php

namespace Tests\Feature\Questions;

use App\Enum\PermissionEnum;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @var Question $question  */
    protected $question;
    /** @var string  */
    protected $permission;
    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->question = create(Question::class);
        $this->permission = PermissionEnum::UPDATE_QUESTION;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_update_question_if_has_all_permission()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->givePermissionTo(PermissionEnum::UPDATE_ANSWER);

        $this->signIn($this->user);

        $this->putJson(route('questions.update', $this->question), $newData = ['text' => 'text'])
             ->assertStatus(200)
             ->assertJsonFragment($newData);

        $this->assertEquals($this->question->fresh()->text, $newData['text']);
    }

    /** @test */
    public function it_can_update_or_create_answers_if_send_answers_array()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->givePermissionTo(PermissionEnum::UPDATE_ANSWER);
        $this->signIn($this->user);

        /** @var Answer $answer */
        $answer = create(Answer::class, ['title' => 'Test title', 'is_correct' => 1]);
        $this->putJson(
            route('questions.update', $answer->question),
            $request = [
                'answers' => [
                    ['title' => 'New answer title', 'is_correct' => 1, 'id' => $answer->id],
                    ['title' => 'New created answer', 'is_correct' => 0]
                ]
            ]
        )->assertStatus(200);

        $this->assertTrue(Answer::query()->where($request['answers'][0])->exists());
        $this->assertTrue(Answer::query()->where($request['answers'][1])->exists());
    }

    /** @test */
    public function it_can_not_update_or_create_answer_if_has_not_permisstion_to_update_answers()
    {
        $this->user->givePermissionTo(PermissionEnum::UPDATE_ANSWER);
        $this->signIn($this->user);

        /** @var Answer $answer */
        $answer = create(Answer::class, ['title' => 'Test title', 'is_correct' => 1]);
        $this->putJson(
            route('questions.update', $answer->question),
            $request = [
                'answers' => [
                    ['title' => 'New answer title', 'is_correct' => 1, 'id' => $answer->id],
                    ['title' => 'New created answer', 'is_correct' => 0]
                ]
            ]
        )->assertStatus(403);
    }

    /** @test */
    public function it_user_can_not_update_question_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->putJson(route('questions.update', $this->question), ['text' => 'text'])
             ->assertStatus(403);

        $this->assertDatabaseHas('questions', $this->question->toArray());
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('questions.update', ['text' => 'text']))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);
        $this->user->givePermissionTo(PermissionEnum::UPDATE_ANSWER);

        $this->signIn($this->user)
             ->putJson(route('questions.update', $this->question), ['count_correct' => 'sss'])
             ->assertStatus(422);

        $this->assertDatabaseHas('questions', $this->question->toArray());
    }
}
