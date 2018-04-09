<?php

namespace Tests\Unit;

use App\Enum\EventEnum;
use App\Models\Course;
use App\Models\User;
use App\Pivots\CourseUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AcceptCourseUserTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;

    public function setUp()
    {
        parent::setUp();

        $this->course = create(Course::class);
    }

    /** @test
     * @throws \App\Exceptions\CourseAcceptException
     */
    public function it_can_accept_user_to_course()
    {
        \Event::fake();

        $this->signIn();
        $this->assertFalse($this->course->courseUser()->where('user_id', auth()->id())->exists());

        $this->course->attachUsers([auth()->id()]);

        $this->assertTrue($this->course->courseUser()->where('user_id', auth()->id())->exists());
    }
}
