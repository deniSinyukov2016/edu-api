<?php

namespace Tests\Unit\Events;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\BuyCourse;
use App\Events\CourseUserEvent\FinishCourse;
use App\Events\CourseUserEvent\NoMoreAttemptsTest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\User;
use App\Pivots\TestUser;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    protected $course;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
        $this->course = create(Course::class);
        create(Lesson::class, ['course_id' => $this->course->id]);
    }

    /** @test
     * @throws \App\Exceptions\CourseAcceptException
     */
    public function it_user_buy_course_expect_event()
    {
        \Event::fake();

        $this->course->attachUsers([auth()->id()]);

        \Event::assertDispatched(BuyCourse::class, function ($e) {
            return $e->course->id === $this->course->id;
        });
    }

    /** @test
     * @throws \App\Exceptions\CourseAcceptException
     */
    public function it_user_buy_course_expect_row_in_table()
    {

        $this->course->attachUsers([auth()->id()]);

        $this->assertDatabaseHas('events', [
            'user_id'       => auth()->id(),
            'course_id'     => $this->course->id,
            'event_type_id' => EventEnum::START_COURSE
        ]);
    }

    /** @test */
    public function it_user_finish_course_expect_event()
    {
        \Event::fake();
        $userIds = create(User::class, [], 5)->pluck('id')->toArray();

        $this->course->setSuccess($userIds);

        \Event::assertDispatched(FinishCourse::class, function ($e) {
            return $e->course->id === $this->course->id;
        });
    }

    /** @test */
    public function it_user_can_not_finish_test_if_no_more_attempts_expected_event()
    {
        \Event::fake();
        $this->signIn();

        /** @var Test $test */
        $test = create(Test::class);
        TestUser::create([
            'test_id'           => $test->id,
            'user_id'           => auth()->id(),
            'start'             => Carbon::now(),
            'end'               => Carbon::now()->addDay(-10),
            'count_attemps'     => 0,
            'is_success'        => false

        ]);

        $test->resetTest(auth()->user());

        \Event::assertDispatched(NoMoreAttemptsTest::class, function ($e) use ($test){
            return $e->course->id === $test->lesson->course->id;
        });
    }
}
