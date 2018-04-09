<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_any_view_categories()
    {
        $categories = create(Category::class, [], 3);
        $response = $this->getJson(route('categories.index'))
             ->assertSee($categories[0]->name)
             ->assertSee($categories[1]->slug)
             ->json();

        $this->assertEquals(3, $response['total']);
    }

    /** @test */
    public function it_can_view_one_category()
    {
        $this->getJson(route('categories.show', $category = create(Category::class)))
             ->assertJsonFragment($category->toArray());
    }

    /** @test */
    public function it_can_view_subcategories()
    {
        $category = create(Category::class);

        $subcategories = create(Category::class, ['parent_id' => $category->id], 3);

        $response = $this->getJson(route('categories.index', ['parent_id' => $category->id]))
                         ->assertSee($subcategories[0]->name)
                         ->assertSee($subcategories[1]->slug)
                         ->json();

        $this->assertEquals(3, $response['total']);
    }

    /** @test */
    public function it_can_view_all_category_in_one_query()
    {
        create(Category::class, [], 20);

        $response = $this->getJson(route('categories.index', ['count' => 'nolimit']))->json();

        $this->assertCount(20, $response);
    }

    /** @test */
    public function it_user_can_view_list_category_by_array_id()
    {
        /** @var Category $categories  */
        $categories = create(Category::class, [], 5);

        $response = $this->getJson(route('categories.index', ['id[0]' => $categories[0]->id, 'id[1]' => $categories[1]->id]))
            ->assertStatus(200)
            ->assertSee($categories[0]->name)
            ->json();

        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    public function it_user_can_view_list_category_by_name_where_like()
    {
        /** @var Category $categories  */
        $categories = create(Category::class, ['name' => 'Name is'.str_random(10)], 5);

        $response = $this->getJson(route('categories.index', ['name' => 'is']))
            ->assertStatus(200)
            ->assertSee($categories[0]->name)
            ->json();

        $this->assertEquals(5, $response['total']);
    }

    /** @test */
    public function it_user_can_view_list_subcategories_by_start_date_created()
    {
        /** @var Category $categories  */
        $category = create(Category::class);

        create(Category::class, ['parent_id' => $category->id,
            'created_at' => '2018-02-26 12:20:36'], 3);

        create(Category::class, ['parent_id' => $category->id,
            'created_at' => '2018-02-27 12:20:36'], 8);

        $response = $this->getJson(route('categories.index', [
            'parent_id' => $category->id, 'created_at[value]' => '2018-02-27 12:20:36'
        ]))->assertStatus(200)->json();

        $this->assertEquals(8, $response['total']);
    }
}
