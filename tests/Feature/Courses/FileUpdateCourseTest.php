<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileUpdateCourseTest extends TestCase
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
    public function it_can_update_file_for_course()
    {
        $response = $this->signIn()
            ->postJson(route('courses.update.file', [
            'course'    => $this->course,
            'file'      => $this->course->files()->first()]), ['files' => $this->file])
            ->assertStatus(200)
            ->json();

        $this->assertDatabaseHas('files', [
            'file'  => str_replace('/storage/', '/public/', $response['files'][0]['file'])
        ]);
    }

    /** @test */
    public function it_can_not_update_file_for_course_if_incorrect_data()
    {
        $this->signIn()
            ->postJson(route('courses.update.file', [
                'course'    => $this->course,
                'file'      => $this->course->files()->first()]), ['files' => "file"])
            ->assertStatus(422);
    }
    /** @test */
    public function it_can_not_update_file_for_course_if_not_auth()
    {
        $this->postJson(route('courses.update.file', [
                'course'    => $this->course,
                'file'      => $this->course->files()->first()]))
            ->assertStatus(401);
    }
}
