<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageDeleteCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;
    /** @var UploadedFile $image */
    private $image;

    public function setUp()
    {
        parent::setUp();
        $this->course     = create(Course::class);
        $this->image      = UploadedFile::fake()->image('image.jpg');
    }

    /** @test */
    public function it_user_can_delete_image_to_course()
    {
        Storage::fake();

        $this->signIn()
            ->deleteJson(route('courses.destroy.image', $this->course))
            ->assertStatus(204);

        $this->assertDatabaseMissing('images', [
            'imageable_id'      => $this->course->id,
            'imageable_type'    => Course::class,
            'image'             => $this->course->getImageDir() . $this->image->hashName()
        ]);

        Storage::disk('local')->assertMissing($this->course->getImageDir() . $this->image->hashName());
    }

    /** @test */
    public function it_user_can_not_delete_image_to_course_if_not_auth()
    {
        $this->deleteJson(route('courses.destroy.image', $this->course))
            ->assertStatus(401);
    }
}
