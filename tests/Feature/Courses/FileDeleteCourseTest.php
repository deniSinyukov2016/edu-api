<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileDeleteCourseTest extends TestCase
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
    public function it_user_can_delete_files_for_course()
    {
        Storage::fake();

        $file = make(File::class, [
            'fileable_id'   => $this->course->id,
            'fileable_type' => Course::class,
        ])->toArray();

        $this->course->files()->create($file);

        $this->assertCount(1, $this->course->files);

        $this->signIn()
             ->deleteJson(route('courses.destroy.file', [
                 'course' => $this->course,
                 'file_ids'    => [$this->course->files()->first()->id]
             ]))
             ->assertStatus(204);
        $this->assertCount(0, $this->course->fresh()->files);

        $this->assertDatabaseMissing('files', [
            'fileable_id'      => $this->course->id,
            'fileable_type'    => Course::class,
            'file'             => $this->course->getFileDir() . $this->file->hashName()
        ]);
        Storage::disk('local')->assertMissing($this->course->getFileDir() . $this->file->hashName());
    }

    /** @test */
    public function it_user_can_not_delete_files_for_course_if_not_auth()
    {
        $this->deleteJson(route('courses.destroy.file', $this->course))
             ->assertStatus(401);
    }
    /** @test */
    public function it_user_can_delete_files_for_course_if_invalid_data()
    {
        $file = make(File::class, [
            'fileable_id'   => $this->course->id,
            'fileable_type' => Course::class,
        ])->toArray();

        $this->course->files()->create($file);

        $this->signIn()
            ->deleteJson(route('courses.destroy.file', [
                'course' => $this->course,
                'file_ids'    => $this->course->files()->first()->id
            ]))
            ->assertStatus(422);
    }

}
