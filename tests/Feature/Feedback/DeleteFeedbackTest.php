<?php

namespace Tests\Feature\Feedback;

use App\Enum\PermissionEnum;
use App\Models\Feedback;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteFeedbackTest extends TestCase
{
    use RefreshDatabase;

    /** @var FeedBack $feedback  */
    protected $feedback;

    /** @var User $user  */
    protected $user;

    /** @var string $permission  */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->feedback = create(Feedback::class);
        $this->permission = PermissionEnum::DELETE_FEEDBACK;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_feedback_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
            ->deleteJson(route('feedback.destroy', $this->feedback))
            ->assertStatus(204);

        $this->assertDatabaseMissing('feedback', $this->feedback->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_feedback_if_has_not_permission()
    {
        $this->signIn($this->user)
            ->deleteJson(route('feedback.destroy', $feedback = create(Feedback::class)))
            ->assertStatus(403);

        $this->assertDatabaseHas('feedback', $feedback->toArray());
    }

    /** @test */

    public function unauth_user_can_not_delete_feedback()
    {
        $this->getJson(route('feedback.destroy', $this->feedback))
             ->assertStatus(401);
    }
}
