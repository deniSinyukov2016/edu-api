<?php

namespace Tests\Unit;

use App\Exceptions\ImageException;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var Category $category */
    private $category;
    /** @var UploadedFile $image */
    private $image;

    public function setUp()
    {
        parent::setUp();
        $this->category   = create(Category::class);
        $this->image    = UploadedFile::fake()->image('image.jpg', 500, 500);
    }

    /** @test
     * @throws \Exception
     */
    public function it_can_add_image_to_category()
    {
        $this->category->addImage($this->image);
        $this->assertEquals(1, $this->category->images()->count());
        $this->assertTrue(\Storage::disk('local')->exists($this->category->getImageDir() . $this->image->hashName()));
    }

    /** @test
     * @throws \Exception
     */
    public function it_can_not_add_image_to_category_if_image_exists()
    {
        $this->expectException(ImageException::class);
        $this->category->addImage($this->image);
        $imageSecond = UploadedFile::fake()->image('image2.jpg', 500, 500);
        $this->category->addImage($imageSecond);
    }
    /** @test */
    public function it_can_delete_image_for_category()
    {
        $this->category->addImage($this->image);
        $this->assertEquals(1, $this->category->images()->count());
        $this->category->deleteImage();
        $this->assertEquals(0, $this->category->images()->count());

        $this->assertFalse(\Storage::disk('local')->exists($this->category->getImageDir() . $this->image->hashName()));
    }

}
