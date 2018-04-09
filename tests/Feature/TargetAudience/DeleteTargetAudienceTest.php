<?php

namespace Tests\Feature\TargetAudience;


use App\Enum\PermissionEnum;
use App\Models\Course;
use App\Models\TargetAudience;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTargetAudienceTest extends TestCase
{
    use RefreshDatabase;

    /** @var array */
    protected $target;

    /** @var string */
    protected $permission;

    /** @var User $user */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->target = create(TargetAudience::class);
        $this->permission = PermissionEnum::DELETE_TARGET;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_target_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
            ->deleteJson(route('targets.destroy', $this->target))
            ->assertStatus(204);

        $this->assertDatabaseMissing('target_audiences', $this->target->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_target_if_has_not_permission()
    {
        $this->signIn($this->user)
            ->deleteJson(route('targets.destroy', $this->target))
            ->assertStatus(403);

        $this->assertDatabaseHas('target_audiences', $this->target->toArray());
    }

    /** @test */
    public function it_can_not_delete_target_if_unauthorized()
    {
        $this->deleteJson(route('targets.destroy', $this->target))
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_delete_target_course()
    {
        $this->signIn($this->user);

        $this->user->givePermissionTo($this->permission);

        $course = create(Course::class);
        $this->deleteJson(route('targets.destroy', [
            'targets' => $this->target,
            'course_id' => $course->id
        ]))
             ->assertStatus(204);

        $this->assertDatabaseMissing('target_audiences', $this->target->toArray());
    }
}
