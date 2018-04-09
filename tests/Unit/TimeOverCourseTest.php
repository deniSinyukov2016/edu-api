<?php

namespace Tests\Unit;

use App\Enum\EventEnum;
use App\Events\CourseUserEvent\TimeOverCourse;
use App\Models\Course;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimeOverCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;

    public function setUp()
    {

        parent::setUp();
        $this->course = create(Course::class);
        $this->signIn();
    }

    /** @test */
    public function it_course_time_over()
    {
        $this->course->courseUser()->create([
            'user_id' => auth()->id(),
            'start_time' => Carbon::now(),
            'close_time' => Carbon::now()->addWeeks(-10),
        ]);

        $this->artisan('time:over');

        $this->assertDatabaseHas('events', [
            'user_id' => auth()->id(),
            'event_type_id' => EventEnum::TIME_OVER_COURSE,
            'course_id' => $this->course->id
        ]);
    }

    /** @test
     * @throws \Exception
     */
    public function it_time_over_expect_event()
    {
        $this->course->courseUser()->create([
            'user_id' => auth()->id(),
            'start_time' => Carbon::now(),
            'close_time' => Carbon::now()->addWeeks(-10),
        ]);

        $this->expectsEvents(TimeOverCourse::class);

        \Event::assertDispatched(TimeOverCourse::class, function ($e) {
            return $e->course->id === $this->course->id;
        });

        $this->artisan('time:over');
    }
}
