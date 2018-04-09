<?php

namespace Tests\Feature\Categories;

use App\Enum\PermissionEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_category_if_has_permission()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::UPDATE_CATEGORY);

            $category = create(Category::class, ['parent_id' => null]);

            $this->putJson(route('categories.update', $category), ['slug' => 'test-slug'])
                 ->assertStatus(200)
                 ->assertJsonFragment($updatedCategory = $category->fresh()->toArray());

            $this->assertDatabaseHas('categories', $updatedCategory);
        });
    }

    /** @test */
    public function it_can_not_update_if_has_not_permission()
    {
        $this->signIn();

        $category = create(Category::class, ['parent_id' => null]);

        $this->putJson(route('categories.update', $category), ['slug' => 'test-slug'])
             ->assertStatus(403);

        $this->assertDatabaseHas('categories', $category->toArray());
    }

    /** @test */
    public function it_can_not_update_with_incorrect_data()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::UPDATE_CATEGORY);

            $category = create(Category::class, ['parent_id' => null]);

            $this->putJson(route('categories.update', $category), ['name' => '1'])
                 ->assertStatus(422);

            $this->assertDatabaseHas('categories', $category->toArray());
        });
    }

    /** @test */
    public function it_can_not_update_if_unauthorized()
    {
        $this->putJson(route('categories.update', 1))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_update_slug_on_existing_category_slug()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::UPDATE_CATEGORY);
            create(Category::class, ['slug' => 'slug-1']);
            $category = create(Category::class, ['slug' => 'slug-2']);

            $this->putJson(route('categories.update', $category), ['slug' => 'slug-1'])
                 ->assertStatus(422);

            $this->assertDatabaseHas('categories', $category->toArray());
        });
    }

    /** @test */
    public function it_can_update_subcategory_if_has_permission()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::UPDATE_CATEGORY);

            $category    = create(Category::class, ['parent_id' => null]);
            $subcategory = create(Category::class, ['parent_id' => $category->id]);

            $this->putJson(route('categories.update', $subcategory), ['slug' => 'test-slug'])
                 ->assertStatus(200)
                 ->assertJsonFragment($updatedSubcategory = $subcategory->fresh()->toArray());

            $this->assertDatabaseHas('categories', $updatedSubcategory);
        });
    }

    /** @test */
    public function it_can_move_subcategory_to_other_category()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::UPDATE_CATEGORY);

            $category1   = create(Category::class, ['parent_id' => null]);
            $category2   = create(Category::class, ['parent_id' => null]);
            $subcategory = create(Category::class, ['parent_id' => $category1->id]);

            $this->assertCount(1, $category1->fresh()->subcategories);
            $this->assertCount(0, $category2->fresh()->subcategories);

            $newData = ['parent_id' => $category2->id];
            $this->putJson(route('categories.update', $subcategory), $newData)
                 ->assertStatus(200)
                 ->assertJsonFragment($newData);

            $this->assertCount(0, $category1->fresh()->subcategories);
            $this->assertCount(1, $category2->fresh()->subcategories);
        });
    }
}
