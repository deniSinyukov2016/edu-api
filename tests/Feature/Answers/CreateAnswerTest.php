<?php

namespace Tests\Feature\Answers;

use App\Enum\PermissionEnum;
use App\Models\Answer;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @var array */
    protected $answer;
    /** @var User $user */
    protected $user;
    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->answer       = make(Answer::class)->toArray();
        $this->user         = create(User::class);
        $this->permission   = PermissionEnum::CREATE_ANSWER;
    }

    /** @test */
    public function it_user_can_create_answer_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('answers.store'), $this->answer)
             ->assertStatus(201)
             ->assertJsonFragment($this->answer);

        $this->assertDatabaseHas('answers', $this->answer);
    }

    /** @test */
    public function it_user_can_not_create_answer_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('answers.store'), $this->answer)
             ->assertStatus(403);

        $this->assertDatabaseMissing('answers', $this->answer);
    }

    /** @test */
    public function it_user_can_not_add_answer_if_not_authorize()
    {
        $this->postJson(route('answers.store'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_answer_can_can_not_be_added_if_invalid_data()
    {
        $answer = make(Answer::class, ['title' => str_random(256)])->toArray();

        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('answers.store'), $answer)
             ->assertStatus(422);

        $this->assertDatabaseMissing('answers', $answer);
    }
}
