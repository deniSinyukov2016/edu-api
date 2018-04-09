<?php

namespace Tests\Feature\Answers;

use App\Enum\PermissionEnum;
use App\Models\Answer;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateAnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @var Answer $answer  */
    protected $answer;
    /** @var string  */
    protected $permission;
    /** @var User $user  */
    protected $user;
    /** @var array $data */
    protected $data;

    public function setUp()
    {
        parent::setUp();

        $this->answer = create(Answer::class);
        $this->permission = PermissionEnum::UPDATE_ANSWER;
        $this->user = create(User::class);
        $this->data = ['title' => 'new Title'];
    }

    /** @test */
    public function it_user_can_update_answer_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('answers.update', $this->answer), $newData = $this->data)
             ->assertStatus(200)
             ->assertJsonFragment($newData);

        $this->assertEquals($this->answer->fresh()->title, $newData['title']);
    }

    /** @test */
    public function it_user_can_not_update_answer_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->putJson(route('answers.update', $this->answer), $this->data)
             ->assertStatus(403);

        $this->assertDatabaseHas('answers', $this->answer->toArray());
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('answers.update', $this->data))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->putJson(route('answers.update', $this->answer), ['title' => str_random(256)])
             ->assertStatus(422);

        $this->assertDatabaseHas('answers', $this->answer->toArray());
    }
}
