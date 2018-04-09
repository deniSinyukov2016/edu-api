<?php

namespace Tests\Feature\Courses;

use App\Enum\EventEnum;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Pivots\LessonUser;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCourseUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course  */
    protected $course;

    /** @var User $userIds  */
    protected $userIds;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);

        $this->userIds = create(User::class, [], 2)->pluck('id')->toArray();

        $this->course->courseUser()->create([
            'user_id'          => $this->userIds[0],
            'close_time'       => Carbon::now()->addDay($this->course->duration),
            'start_time'       => Carbon::now(),
            'course_status_id' => EventEnum::START_COURSE
        ]);
    }

    /** @test */
    public function it_can_dispatch_users_for_course()
    {
        $this->course->lessons()->create(make(Lesson::class)->toArray());

        LessonUser::query()->create([
            'lesson_id' => $this->course->lessons->first()->id,
            'user_id'   => $this->userIds[0]
        ]);

        $this->assertCount(1, $this->course->courseUser);

        $this->assertDatabaseHas('lesson_user', [
            'lesson_id' => $this->course->lessons->first()->id,
            'user_id'   => $this->userIds[0]
        ]);

        $this->signIn()
            ->deleteJson(route('courses.course.users.delete', [
                $this->course,
                'users' => [$this->userIds[0]]
            ]))
            ->assertStatus(204);

        $this->assertDatabaseMissing('lesson_user', [
            'lesson_id' => $this->course->lessons->first()->id,
            'user_id'   => $this->userIds[0]
        ]);

        $this->assertCount(0,$this->course->fresh()->courseUser);
    }

    /** @test */
    public function it_can_not_dispatch_users_for_course_if_invalid_data()
    {
        $this->signIn()
            ->deleteJson(route('courses.course.users.delete', [
                $this->course,
                'users' => create(User::class)->id
            ]))
            ->assertStatus(422);
    }

    /** @test */
    public function it_can_not_dispatch_users_for_course_if_not_auth()
    {
        $this->deleteJson(route('courses.course.users.delete', [
                $this->course,
                'users' => create(User::class)->id
            ]))
            ->assertStatus(401);
    }

}
