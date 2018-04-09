<?php

namespace Tests\Feature\Lessons;

use App\Models\File;
use App\Models\Lesson;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileDeleteLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var Lesson $lesson */
    private $lesson;
    /** @var UploadedFile $image */
    private $file;

    public function setUp()
    {
        parent::setUp();
        $this->lesson       = create(Lesson::class);
        $this->file         = UploadedFile::fake()->create('document.pdf');
    }

    /** @test */
    public function it_user_can_delete_files_for_lesson()
    {
        Storage::fake();

        $file = make(File::class, [
            'fileable_id'   => $this->lesson->id,
            'fileable_type' => Lesson::class,
        ])->toArray();

        $this->lesson->files()->create($file);

        $this->assertEquals(1, $this->lesson->files()->count());

        $this->signIn()
            ->deleteJson(route('lessons.destroy.file', [
                'course'    => $this->lesson,
                'file_ids'  => [$this->lesson->files()->first()->id]
            ]))
            ->assertStatus(204);

        $this->assertEquals(0, $this->lesson->files()->count());

        $this->assertDatabaseMissing('files', [
            'fileable_id'      => $this->lesson->id,
            'fileable_type'    => Lesson::class,
            'file'             => $this->lesson->getFileDir() . $this->file->hashName()
        ]);
//
        Storage::disk('local')->assertMissing($this->lesson->getFileDir() . $this->file->hashName());
    }

    /** @test */
    public function it_user_can_not_delete_files_for_lesson_if_not_auth()
    {
        $this->deleteJson(route('lessons.destroy.file', $this->lesson))
             ->assertStatus(401);
    }
    /** @test */
    public function it_user_can_delete_files_for_lesson_if_invalid_data()
    {
        $file = make(File::class, [
            'fileable_id'   => $this->lesson->id,
            'fileable_type' => Lesson::class,
        ])->toArray();

        $this->lesson->files()->create($file);

        $this->signIn()
            ->deleteJson(route('lessons.destroy.file', [
                'course'    => $this->lesson,
                'file_ids'  => $this->lesson->files()->first()->id
            ]))
            ->assertStatus(422);
    }
}
