<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_not_create_module_if_course_not_exist()
    {
        $this->expectException(ModelNotFoundException::class);

        create(Module::class, ['course_id' => 999999]);
    }
//    /** @test */
    public function it_can_not_add_module_if_course_has_lessons()
    {
        $course = create(Course::class);

        create(Lesson::class, ['course_id' => $course->id]);
        $this->expectException(Exception::class);
        create(Module::class, ['course_id' => $course->id]);

    }
}
