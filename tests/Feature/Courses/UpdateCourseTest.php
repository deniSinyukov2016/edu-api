<?php

namespace Tests\Feature\Courses;

use App\Enum\PermissionEnum;
use App\Events\UpdateCourseEvent;
use App\Models\Course;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course  */
    protected $course;
    /** @var Permission $permission  */
    protected $permission;
    /** @var User $user  */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);
        $this->permission = PermissionEnum::UPDATE_COURSE;
        $this->user = create(User::class);
    }

    /** @test */
    public function it_user_can_update_course_if_has_permission()
    {
        \Event::fake();
        $this->user->givePermissionTo($this->permission);

        $this->signIn($this->user);

        $this->putJson(route('courses.update', $this->course), $newData = ['price' => 1500])
             ->assertStatus(200)
             ->assertJsonFragment($newData);

        \Event::assertDispatched(UpdateCourseEvent::class, function ($e) {
            return $e->getEntity()->title === $this->course->title;
        });

        $this->assertEquals($this->course->fresh()->price, $newData['price']);
    }

    /** @test */
    public function it_user_can_not_update_course_if_has_not_permission()
    {
        $this->signIn($this->user)
             ->putJson(route('courses.update', $this->course), ['price' => 1500])
             ->assertStatus(403);

        $this->assertDatabaseHas('courses', $this->course->toArray());
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('courses.update', 1))
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_if_invalid_data()
    {
        $this->user->givePermissionTo($this->permission);
        $this->signIn($this->user);

        $this->putJson(route('courses.update', $this->course), ['price' => '1ooo'])
            ->assertStatus(422);

        $this->assertDatabaseHas('courses', $this->course->toArray());
    }

    /** @test */
    public function it_user_can_update_course_and_targets_if_has_permission()
    {
        $this->user->givePermissionTo($this->permission);
        $newData = ['price' => 1500, 'target_audiences' => ['target 1', 'target 2']];

        $this->signIn($this->user);

        $response = $this->putJson(route('courses.update', $this->course), $newData)
             ->assertStatus(200)
             ->assertSee($newData['target_audiences'][0])
             ->assertSee($newData['target_audiences'][1])
             ->json();

        $this->assertArrayHasKey('target_audiences', $response);

        $this->assertEquals(2, $this->course->fresh()->targetAudiences()->count());
    }
}
