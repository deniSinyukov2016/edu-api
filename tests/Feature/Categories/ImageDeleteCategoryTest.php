<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageDeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var Category $category */
    private $category;
    /** @var UploadedFile $image */
    private $image;

    public function setUp()
    {
        parent::setUp();
        $this->category     = create(Category::class);
        $this->image        = UploadedFile::fake()->image('image.jpg');
    }

    /** @test */
    public function it_user_can_delete_image_to_category()
    {
        Storage::fake();

        $this->signIn()
            ->deleteJson(route('categories.destroy.image', $this->category))
            ->assertStatus(204);

        $this->assertDatabaseMissing('images', [
            'imageable_id'      => $this->category->id,
            'imageable_type'    => Category::class,
            'image'             => $this->category->getImageDir() . $this->image->hashName()
        ]);

        Storage::disk('local')->assertMissing($this->category->getImageDir() . $this->image->hashName());
    }

    /** @test */
    public function it_user_can_not_delete_image_to_category_if_not_auth()
    {
        $this->deleteJson(route('categories.destroy.image', $this->category))
             ->assertStatus(401);
    }
}
