<?php

namespace Tests\Feature\LessonUser;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Pivots\LessonUser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LessonUserStoreTest extends TestCase
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
    public function it_can_set_lesson_completed()
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

        $this->postJson(route('lessons.complete.lesson', [
            $lesson,
            'users' => [auth()->id()]
        ]))->assertStatus(204);

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

    /** @test */
    public function it_can_not_set_lesson_completed_if_not_auth()
    {
        $this->postJson(route('lessons.complete.lesson', [
            create(Lesson::class)
        ]))->assertStatus(401);
    }

    /** @test */
    public function it_can_not_set_lesson_completed_if_invalid_data()
    {
        $this->signIn()->postJson(route('lessons.complete.lesson', [
            create(Lesson::class),
            'users' => create(User::class)
        ]))->assertStatus(422);
    }
}
