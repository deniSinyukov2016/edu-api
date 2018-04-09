<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class LessonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lesson_can_not_be_added_if_does_exist_type_lesson()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Lesson::class, ['type_lessons_id' => null]);
    }

    /** @test */
    public function it_lesson_can_not_be_added_if_does_exist_module_id()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Lesson::class, ['module_id' => null]);
    }

    /** @test */
    public function it_lesson_can_not_be_added_if_does_exist_course_id()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Lesson::class, ['course_id' => null]);
    }

    /** @test */
    public function it_module_and_lesson_must_has_common_course()
    {
        /** @var Module $module  */
        $module = create(Module::class, ['course_id' => create(Course::class)->id]);
        /** @var Lesson $lesson  */
        $lesson = create(Lesson::class, ['module_id' => $module->id]);

        $this->assertEquals($module->course->id, $lesson->module->course->id);
    }

//    /** @test */
    public function it_can_not_add_lesson_if_course_has_modules()
    {
        $course = create(Course::class);
        create(Module::class, ['course_id' => $course->id]);

        $this->expectException(Exception::class);
        create(Lesson::class, ['course_id' => $course->id]);
    }
}
