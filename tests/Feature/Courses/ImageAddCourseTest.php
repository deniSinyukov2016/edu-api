<?php

namespace Tests\Feature\Courses;

use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageAddCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @var Course $course */
    private $course;
    /** @var UploadedFile $image */
    private $image;

    public function setUp()
    {
        parent::setUp();
        $this->course   = create(Course::class);
        $this->image    = UploadedFile::fake()->image('image.jpg');
    }

    /** @test */
    public function it_user_can_add_image_to_course()
    {
        Storage::fake();

        $this->signIn()
             ->postJson(route('courses.store.image', $this->course), [
                 'image' => $this->image
             ])->assertStatus(200)
               ->assertSee($this->image->hashName());

        $this->assertDatabaseHas('images', [
            'imageable_id'      => $this->course->id,
            'imageable_type'    => Course::class,
            'image'             => $this->course->getImageDir() . $this->image->hashName()
        ]);

        Storage::disk('local')->assertExists($this->course->getImageDir() . $this->image->hashName());
    }

    /** @test */
    public function it_user_can_not_add_image_to_course_if_invalid_data()
    {
        $response = $this->signIn()
             ->postJson(route('courses.store.image', $this->course), [
                 'image' => UploadedFile::fake()->create('document.pdf')
             ])->assertStatus(422)
             ->json();

        $this->assertArrayHasKey('image', $response['errors']);
    }

    /** @test */
    public function it_user_can_not_add_image_to_course_if_not_auth()
    {
        $this->postJson(route('courses.store.image', $this->course))
             ->assertStatus(401);
    }
}
