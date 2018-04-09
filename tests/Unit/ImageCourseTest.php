<?php

namespace Tests\Unit;

use App\Exceptions\ImageException;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageCourseTest extends TestCase
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
        $this->image    = UploadedFile::fake()->image('image.jpg', 500, 500);
    }

    /** @test
     * @throws \Exception
     */
    public function it_can_add_image_to_course()
    {
        $this->course->addImage($this->image);
        $this->assertEquals(1, $this->course->images()->count());
        $this->assertTrue(\Storage::disk('local')->exists($this->course->getImageDir() . $this->image->hashName()));
    }

    /** @test
     * @throws \Exception
     */
    public function it_can_not_add_image_to_course_if_image_exists()
    {
        $this->expectException(ImageException::class);
        $this->course->addImage($this->image);
        $imageSecond = UploadedFile::fake()->image('image2.jpg', 500, 500);
        $this->course->addImage($imageSecond);
    }

    /** @test */
    public function it_can_delete_image_for_course()
    {
        $this->course->addImage($this->image);
        $this->assertEquals(1, $this->course->images()->count());
        $this->course->deleteImage();
        $this->assertEquals(0, $this->course->images()->count());

        $this->assertFalse(\Storage::disk('local')->exists($this->course->getImageDir() . $this->image->hashName()));
    }
}
