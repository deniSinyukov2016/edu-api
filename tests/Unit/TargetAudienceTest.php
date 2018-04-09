<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\TargetAudience;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TargetAudienceTest extends TestCase
{
    use RefreshDatabase;

    /** @var TargetAudienceTest $target */
    private $target;

    public function setUp()
    {
        parent::setUp();
        $this->target   = create(TargetAudience::class);

    }
    /** @test */
    public function it_add_target_to_courses()
    {
        /** @var Course $course */
        $course = create(Course::class);

        $course->addTargets([$this->target->title]);

        $this->assertDatabaseHas('course_target_audience', [
            'target_audience_id'    => $this->target->id,
            'course_id'             => $course->id
        ]);
    }

}
