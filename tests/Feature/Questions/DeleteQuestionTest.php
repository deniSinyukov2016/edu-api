<?php

namespace Tests\Feature\Questions;

use App\Enum\PermissionEnum;
use App\Models\Question;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteQuestionTest extends TestCase
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
        $this->permission = PermissionEnum::DELETE_QUESTION;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_question_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('questions.destroy'), ['id' => [$this->question->id]])
             ->assertStatus(204);

        $this->assertDatabaseMissing('questions', $this->question->toArray());
    }

    /** @test */
    public function it_user_can_delete_many_questions_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $question = create(Question::class);

        $this->signIn($this->user)
             ->deleteJson(route('questions.destroy'), ['id' => [$this->question->id, $question->id]])
             ->assertStatus(204);

        $this->assertDatabaseMissing('questions', $this->question->toArray());
        $this->assertDatabaseMissing('questions', $question->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_question_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('questions.destroy'), ['id' => [$this->question->id]])
             ->assertStatus(403);

        $this->assertDatabaseHas('questions', $this->question->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->deleteJson(route('questions.destroy'), ['id' => [$this->question->id]])
             ->assertStatus(401);
    }
}
