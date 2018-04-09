<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Lesson;
use App\Pivots\LessonUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);
    }

    /** @test */
    public function it_can_set_lesson_completed_method()
    {
        $this->signIn();

        /** @var Lesson $lessons */
        $lesson = create(Lesson::class, ['course_id' => $this->course->id]);
        /** @var Lesson $lessonSecond */
        $lessonSecond = create(Lesson::class, ['course_id' => $this->course->id]);

        create(LessonUser::class, [
            'user_id'   => auth()->id(),
            'lesson_id' => $lessonSecond->id,
        ]);
        create(LessonUser::class, [
            'user_id'   => auth()->id(),
            'lesson_id' => $lesson->id,
            'is_close'  => false
        ]);
        $lesson->setComplete([auth()->id()]);

        $this->assertDatabaseHas('lesson_user', [
            'user_id'     => auth()->id(),
            'lesson_id'   => $lesson->id,
            'is_complete' => true
        ]);

        $this->assertDatabaseHas('lesson_user', [
            'user_id'     => auth()->id(),
            'lesson_id'   => $lessonSecond->id,
            'is_complete' => false,
            'is_close'    => false

        ]);
    }
}
