<?php

namespace Tests\Feature\Feedback;

use App\Enum\PermissionEnum;
use App\Models\Feedback;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewFeedbackTest extends TestCase
{
    use RefreshDatabase;

    /** @var FeedBack $feedback  */
    protected $feedback;

    /** @var User $user  */
    protected $user;

    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->feedback = create(Feedback::class);
        $this->permission = PermissionEnum::VIEW_FEEDBACK;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_view_feedback_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        create(Feedback::class, [], 3);

        $response = $this->signIn($this->user)
            ->getJson(route('feedback.index'))
            ->assertStatus(200)
            ->json();

        $this->assertCount(4, $response['data']);
    }

    /** @test */
    public function if_has_not_permission_to_view_feedback_list_throw_exception()
    {
        $this->signIn($this->user)
            ->getJson(route('feedback.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function unauth_user_can_not_view_feedback_list()
    {
        $this->getJson(route('feedback.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_one_feedback()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
            ->getJson(route('feedback.show', $feedback = create(Feedback::class)))
            ->assertJsonFragment($feedback->toArray());
    }

    /** @test */
    public function if_has_not_permission_to_view_one_feedback_throw_exception()
    {
        $this->signIn($this->user)
             ->getJson(route('feedback.show', $feedback = create(Feedback::class)))
             ->assertStatus(403);
    }

    /** @test */
    public function unauth_user_can_not_view_feedback()
    {
        $this->getJson(route('feedback.index'))
             ->assertStatus(401);
    }
}
