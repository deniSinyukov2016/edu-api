<?php

namespace Tests\Feature\Courses;

use App\Enum\PermissionEnum;
use App\Models\Course;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course  */
    protected $course;

    /** @var string $permission  */
    protected $permission;

    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);
        $this->permission = PermissionEnum::DELETE_COURSE;

        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_delete_course_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user)
             ->deleteJson(route('courses.destroy', $this->course))
             ->assertStatus(204);

        $this->assertDatabaseMissing('courses', $this->course->toArray());
    }

    /** @test */
    public function it_user_can_not_delete_course_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->deleteJson(route('courses.destroy', $this->course))
             ->assertStatus(403);

        $this->assertDatabaseHas('courses', $this->course->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->deleteJson(route('courses.destroy', $this->course))
             ->assertStatus(401);
    }
}
