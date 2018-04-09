<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileAddCourseTest extends TestCase
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
    }

    /** @test */
    public function it_user_can_add_files_to_course()
    {
        Storage::fake();

        $this->signIn()
            ->postJson(route('courses.store.file', $this->course), [
                'files' => [$this->file, $file2 = UploadedFile::fake()->create('document2.pdf')]
            ])->assertStatus(200)
              ->assertSee($this->file->hashName())
              ->assertSee($file2->hashName());

        $this->assertDatabaseHas('files', [
            'fileable_id'       => $this->course->id,
            'fileable_type'     => Course::class,
            'file'              => $this->course->getFileDir() . $this->file->hashName()
        ]);

        Storage::disk('local')->assertExists($this->course->getFileDir() . $this->file->hashName());
    }

    /** @test */
    public function it_user_can_not_add_files_to_course_if_invalid_data()
    {
        $response = $this->signIn()
            ->postJson(route('courses.store.file', $this->course), [
                'file' => $this->file
            ])->assertStatus(422)
            ->json();

        $this->assertArrayHasKey('files', $response['errors']);
    }

    /** @test */
    public function it_user_can_not_add_files_to_course_if_not_auth()
    {
        $this->postJson(route('courses.store.file', $this->course))
            ->assertStatus(401);
    }
}
