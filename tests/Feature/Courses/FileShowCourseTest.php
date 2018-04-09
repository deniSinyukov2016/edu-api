<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileShowCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;
    /** @var UploadedFile $image */
    private $file;

    public function setUp()
    {
        parent::setUp();
        $this->course       = create(Course::class);
        $this->file         = UploadedFile::fake()->create('document.pdf');

        $file = make(File::class, [
            'fileable_id'   => $this->course->id,
            'fileable_type' => Course::class,
        ])->toArray();

        $this->course->files()->create($file);
    }

    /** @test */
    public function it_user_can_view_files_one_course()
    {
        $response = $this->signIn()
            ->getJson(route('courses.show.file', $this->course))
            ->assertStatus(200)
            ->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    public function it_user_can_not_view_files_one_course_if__not_authorized()
    {
        $this->getJson(route('courses.show.file', $this->course))
             ->assertStatus(401);
    }
}
