<?php

namespace Tests\Feature\Categories;

use App\Enum\PermissionEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_category_if_has_permission()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::CREATE_CATEGORY);

            $category = make(Category::class, ['parent_id' => null])->toArray();

            $this->postJson(route('categories.store'), $category)
                 ->assertStatus(201)
                 ->assertJsonFragment($category);

            $this->assertDatabaseHas('categories', $category);
        });
    }

    /** @test */
    public function it_can_not_add_category_if_has_not_permission()
    {
        $this->signIn();

        $category = make(Category::class, ['parent_id' => null])->toArray();

        $this->postJson(route('categories.store'), $category)
             ->assertStatus(403);

        $this->assertDatabaseMissing('categories', $category);
    }

    /** @test */
    public function it_can_not_add_category_if_not_authorize()
    {
        $this->postJson(route('categories.store'))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_not_add_category_with_exist_slug()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::CREATE_CATEGORY);
            create(Category::class, ['slug' => 'slug']);
            $category = make(Category::class, ['parent_id' => null, 'slug' => 'slug'])->toArray();

            $this->postJson(route('categories.store'), $category)
                 ->assertStatus(422);

            $this->assertDatabaseMissing('categories', $category);
        });
    }

    /** @test */
    public function it_can_add_subcategory_if_has_permission()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::CREATE_CATEGORY);
            $category = create(Category::class, ['parent_id' => null]);
            $subcategory = make(Category::class, ['parent_id' => $category->id])->toArray();

            $this->postJson(route('categories.store'), $subcategory)
                 ->assertStatus(201)
                 ->assertJsonFragment($subcategory);

            $this->assertDatabaseHas('categories', $subcategory);
        });
    }
}
