<?php

namespace Tests\Feature\TargetAudience;

use App\Models\TargetAudience;
use App\Enum\PermissionEnum;
use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTargetAudienceTest extends TestCase
{
    use RefreshDatabase;

    /** @var array  */
    protected $target;

    /** @var string  */
    protected $permission;

    /** @var User $user  */
    protected $user;


    public function setUp()
    {
        parent::setUp();

        $this->target = make(TargetAudience::class)->toArray();
        $this->permission = PermissionEnum::CREATE_TARGET;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_create_target_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->postJson(route('targets.store'), $this->target)
             ->assertStatus(201)
             ->assertJsonFragment($this->target);

        $this->assertDatabaseHas('target_audiences', $this->target);
    }

    /** @test */
    public function it_user_can_not_create_target_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->postJson(route('targets.store'), $this->target)
             ->assertStatus(403);

        $this->assertDatabaseMissing('target_audiences', $this->target);
    }

    /** @test */
    public function it_user_can_not_add_target_if_not_authorize()
    {
        $this->postJson(route('targets.store'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_create_target_if_not_exist_throw_exception()
    {
        $this->user->givePermissionTo(PermissionEnum::CREATE_TARGET);

        $target = make(TargetAudience::class, ['title' => null])->toArray();

        $this->signIn($this->user)
             ->postJson(route('targets.store'), $target)
             ->assertStatus(422);

        $this->assertDatabaseMissing('target_audiences', $target);
    }

}
