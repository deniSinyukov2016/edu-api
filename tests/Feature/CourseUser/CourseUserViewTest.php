<?php

namespace Tests\Feature\CourseUser;

use App\Enum\EventEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Pivots\LessonUser;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseUserViewTest extends TestCase
{
    use RefreshDatabase;

    /** @var User $user */
    private $user;
    /** @var Course $course */
    private $course;

    public function setUp()
    {
        parent::setUp();

        $this->user   = create(User::class);
        $this->course = create(Course::class);
        $this->user->courseUser()->create([
            'course_id'         => $this->course->id,
            'start_time'        => Carbon::now(),
            'close_time'        => Carbon::now()->addDay(10),
            'course_status_id'  => EventEnum::START_COURSE
        ]);
    }

    /** @test */
    public function it_can_show_one_course_for_user()
    {
        /** @var Lesson $lesson */
        $lesson = create(Lesson::class, ['course_id' => $this->course->id]);
        create(LessonUser::class, [
            'lesson_id' => $lesson->id,
            'user_id'   => $this->user->id
        ]);
        $lesson->setComplete([$this->user->id]);

        $response = $this->signIn($this->user)
             ->getJson(route('users.courses.show', [$this->user, $this->course]))
             ->assertStatus(200)
             ->assertJson($this->course->toArray())
             ->json();

        $this->assertArrayHasKey('lessons', $response);
        $this->assertArrayHasKey('modules', $response);
        $this->assertArrayHasKey('course_user', $response);
        $this->assertTrue((bool)$response['lessons'][0]['status']);
    }

    /** @test */
    public function it_can_not_show_one_course_for_user_if_not_auth()
    {
        $this->getJson(route('users.courses.show', [$this->user, $this->course]))
             ->assertStatus(401);
    }
}
