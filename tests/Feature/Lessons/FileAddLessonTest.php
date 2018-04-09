<?php

namespace Tests\Feature\Lessons;

use App\Models\Lesson;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileAddLessonTest extends TestCase
{
    use RefreshDatabase;

    /** @var Lesson $lesson */
    private $lesson;
    /** @var UploadedFile $image */
    private $file;

    public function setUp()
    {
        parent::setUp();
        $this->lesson   = create(Lesson::class);
        $this->file     = UploadedFile::fake()->create('document.pdf');
    }

    /** @test */
    public function it_user_can_add_files_to_lesson()
    {
        Storage::fake();

        $this->signIn()
            ->postJson(route('lessons.store.file', $this->lesson), [
                'files' => [$this->file, $file2 = UploadedFile::fake()->create('document2.pdf')]
            ])->assertStatus(200)
            ->assertSee($this->file->hashName())
            ->assertSee($file2->hashName());

        $this->assertDatabaseHas('files', [
            'fileable_id'       => $this->lesson->id,
            'fileable_type'     => Lesson::class,
            'file'              => $this->lesson->getFileDir() . $this->file->hashName()
        ]);

        Storage::disk('local')->assertExists($this->lesson->getFileDir() . $this->file->hashName());
    }

    /** @test */
    public function it_user_can_not_add_files_to_lesson_if_invalid_data()
    {
        $response = $this->signIn()
            ->postJson(route('lessons.store.file', $this->lesson), [
                'file' => $this->file
            ])->assertStatus(422)
            ->json();

        $this->assertArrayHasKey('files', $response['errors']);
    }

    /** @test */
    public function it_user_can_not_add_files_to_lesson_if_not_auth()
    {
        $this->postJson(route('lessons.store.file', $this->lesson))
             ->assertStatus(401);
    }
}
