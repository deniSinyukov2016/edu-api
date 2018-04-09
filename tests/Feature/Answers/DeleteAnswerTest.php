<?php

namespace Tests\Feature\Answers;

use App\Enum\PermissionEnum;
use App\Models\Answer;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteAnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @var Answer $answer  */
    protected $answer;
    /** @var string */
    protected $permission;
    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->answer = create(Answer::class);
        $this->permission = PermissionEnum::DELETE_ANSWER;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_answer_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('answers.destroy'), ['id' => [$this->answer->id]])
             ->assertStatus(204);

        $this->assertDatabaseMissing('answers', $this->answer->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_answer_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('answers.destroy'))
             ->assertStatus(403);

        $this->assertDatabaseHas('answers', $this->answer->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->deleteJson(route('answers.destroy'))
             ->assertStatus(401);
    }
}
