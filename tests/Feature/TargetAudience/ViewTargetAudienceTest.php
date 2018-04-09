<?php

namespace Tests\Feature\TargetAudience;

use App\Enum\PermissionEnum;
use App\Models\TargetAudience;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTargetAudienceTest extends TestCase
{
    use RefreshDatabase;

    /** @var TargetAudience $target  */
    protected $target;

    /** @var User $user  */
    protected $user;

    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->target = create(TargetAudience::class);
        $this->permission = PermissionEnum::VIEW_TARGET;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_view_target_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        create(TargetAudience::class, [], 3);

        $response = $this->signIn($this->user)
            ->getJson(route('targets.index'))
            ->assertStatus(200)
            ->json();

        $this->assertCount(4, $response['data']);
    }

    /** @test */
    public function if_has_not_permission_to_view_target_list_throw_exception()
    {
        $this->signIn($this->user)
             ->getJson(route('targets.index'))
             ->assertStatus(403);
    }

    /** @test */
    public function unauth_user_can_not_view_target_list()
    {
        $this->getJson(route('targets.index'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_user_can_view_one_target()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->getJson(route('targets.show', $target = create(TargetAudience::class)))
             ->assertJsonFragment($target->toArray());
    }

    /** @test */
    public function if_has_not_permission_to_view_one_target_throw_exception()
    {
        $this->signIn($this->user)
             ->getJson(route('targets.show', $target = create(TargetAudience::class)))
             ->assertStatus(403);
    }

    /** @test */
    public function unauth_user_can_not_view_target()
    {
        $this->getJson(route('targets.index'))
             ->assertStatus(401);
    }
}
