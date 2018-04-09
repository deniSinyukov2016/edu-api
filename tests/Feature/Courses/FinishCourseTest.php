<?php

namespace Tests\Feature\Courses;

use App\Enum\EventEnum;
use App\Models\Course;
use App\Models\User;
use App\Pivots\CourseUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinishCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course  */
    protected $course;
    /** @var User $users */
    protected $users;

    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);

        $this->users = create(User::class, [], 5);
        $this->users->each(function (User $user) {
            $this->course->courseUser()->create([
                'user_id'           => $user->id,
                'course_status_id'  => EventEnum::START_COURSE
            ]);
        });
    }

    /** @test */
    public function it_users_can_finish_course()
    {
        $ids = array_pluck($this->users->toArray(), 'id');
        $this->assertCount(5, $this->course->courseUser);

        $this->signIn()->postJson(route('courses.course.finish', [
            'course'    => $this->course,
            'user_id'   => $ids
        ]))->assertStatus(204);

        $this->assertDatabaseHas('events', [
            'user_id'       => 3,
            'course_id'     => $this->course->id,
            'event_type_id' => EventEnum::FINISH_COURSE
        ]);

        $this->assertEquals(2, CourseUser::query()->first()->course_status_id);
    }

    /** @test */
    public function it_can_not_finish_course_if_invalid_data()
    {
        $this->signIn()->postJson(route('courses.course.finish', [
            'course'    => $this->course,
            'user_id'   => create(User::class)->id
        ]))->assertStatus(422);
    }

    /** @test */
    public function it_can_not_finish_course_if_not_auth()
    {
        $this->postJson(route('courses.course.finish', [
            'course'    => $this->course,
            'user_id'   => [create(User::class)->id]
        ]))->assertStatus(401);
    }
}
