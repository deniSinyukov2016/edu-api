<?php

namespace Tests\Feature\Lessons;

use App\Models\File;
use App\Models\Lesson;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileViewLessonTest extends TestCase
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

        $file = make(File::class, [
            'fileable_id'   => $this->lesson->id,
            'fileable_type' => Lesson::class,
        ])->toArray();

        $this->lesson->files()->create($file);
    }

    /** @test */
    public function it_user_can_view_files_one_lesson()
    {
        $response = $this->signIn()
            ->getJson(route('lessons.show.file', $this->lesson))
            ->assertStatus(200)
            ->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    public function it_user_can_not_view_files_one_lesson_if__not_authorized()
    {
        $this->getJson(route('lessons.show.file', $this->lesson))
             ->assertStatus(401);
    }

}
