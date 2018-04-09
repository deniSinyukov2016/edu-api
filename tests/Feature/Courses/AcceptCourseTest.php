<?php

namespace Tests\Feature\Courses;

use App\Enum\EventEnum;
use App\Enum\PermissionEnum;
use App\Events\CourseUserEvent\BuyCourse;
use App\Exceptions\CourseAcceptException;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Pivots\CourseUser;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course  */
    protected $course;

    /** @var string */
    protected $permission;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);
        create(Lesson::class, ['course_id' => $this->course->id]);
        $this->permission = PermissionEnum::CREATE_COURSE;
    }

    /** @test */
    public function it_can_accept_many_users_to_course()
    {
        $usersIds = create(User::class, [], 3)->pluck('id')->toArray();

        $this->signIn();
        $this->postJson(route('courses.accept', [
            'course'    => $this->course,
            'users'     => $usersIds
        ]))->assertStatus(204);

        $this->assertDatabaseHas('events', [
            'user_id'       => $usersIds[2],
            'course_id'     => $this->course->id,
            'event_type_id' => EventEnum::START_COURSE
        ]);

        $this->assertCount(3, CourseUser::query()->whereIn('user_id', $usersIds)->get());
    }

    /** @test */
    public function it_can_not_accept_user_course_if_invalid_data()
    {
        $this->signIn();
        $this->postJson(route('courses.accept', [
            'course'    => $this->course,
            'users'     => ''
        ]))->assertStatus(422);
    }

    /** @test */
    public function it_can_not_accept_course_user_if_not_authorized()
    {
        $this->postJson(route('courses.accept', [
            'course'    => $this->course,
        ]))->assertStatus(401);
    }

    /** @test */
    public function it_can_view_users_accepted_to_course()
    {
        $this->assertCount(0, $this->course->acceptorsUser());

        create(User::class, [], 5)->pluck('id')->each(function ($id) {
            $this->course->courseUser()->create(['user_id' => $id]);
        });

        $this->assertCount(5, $this->course->acceptorsUser());

        $response = $this->signIn()
             ->getJson(route('courses.accept.users', $this->course))
             ->assertStatus(200)
             ->json();

        $this->assertCount(5, $response);
    }

    /** @test */
    public function it_can_not_accept_users_to_course_if_has_not_lessons()
    {
        $this->course->lessons()->delete();

        $userIds = create(User::class, [], 2)->pluck('id')->toArray();

        $this->signIn()->postJson(route('courses.accept', [
            'course'    => $this->course,
            'users'     =>$userIds
        ]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('lesson_user', [
            'user_id'   => auth()->id(),
        ]);
    }

    /** @test */
    public function it_can_not_view_users_accepted_to_course_if_not_auth()
    {
        $this->getJson(route('courses.accept.users', $this->course))
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_view_courses_accepted_for_user()
    {
        $this->signIn();

        $this->assertCount(0, auth()->user()->acceptorsCourse());

        create(Course::class, [], 5)->pluck('id')->each(function ($id) {
            auth()->user()->courseUser()->create(['course_id' => $id]);
        });

        $this->assertCount(5, auth()->user()->acceptorsCourse());

        $response = $this
            ->getJson(route('users.users.courses', auth()->user()))
            ->assertStatus(200)
            ->json();

        $this->assertCount(5, $response);
    }

    /** @test */
    public function it_can_not_view_courses_accepted_for_user_if_not_auth()
    {
        $this->getJson(route('users.users.courses', create(User::class)))
             ->assertStatus(401);
    }
}
