<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageAddCategoryTest extends TestCase
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
    public function it_user_can_add_image_to_category()
    {
        Storage::fake();

        $this->signIn()
            ->postJson(route('categories.store.image', $this->category), [
                'image' => $this->image
            ])->assertStatus(200)
              ->assertSee($this->image->hashName());

        $this->assertDatabaseHas('images', [
            'imageable_id'      => $this->category->id,
            'imageable_type'    => Category::class,
            'image'             => $this->category->getImageDir() . $this->image->hashName()
        ]);

        Storage::disk('local')->assertExists($this->category->getImageDir() . $this->image->hashName());
    }

    /** @test */
    public function it_user_can_not_add_image_to_category_if_invalid_data()
    {
        $response = $this->signIn()
            ->postJson(route('categories.store.image', $this->category), [
                'image' => UploadedFile::fake()->create('document.pdf')
            ])->assertStatus(422)
            ->json();

        $this->assertArrayHasKey('image', $response['errors']);
    }

    /** @test */
    public function it_user_can_not_add_image_to_category_if_not_auth()
    {
        $this->postJson(route('categories.store.image', $this->category))
             ->assertStatus(401);
    }
}
