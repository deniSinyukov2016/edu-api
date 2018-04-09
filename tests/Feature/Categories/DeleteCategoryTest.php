<?php

namespace Tests\Feature\Categories;

use App\Enum\PermissionEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_category_if_has_permission()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::DELETE_CATEGORY);

            $category = create(Category::class, ['parent_id' => null])->toArray();

            $this->deleteJson(route('categories.destroy', $category['id']))
                 ->assertStatus(204);

            $this->assertDatabaseMissing('categories', $category);
        });
    }

    /** @test */
    public function it_can_not_delete_if_has_not_permission()
    {
        $this->signIn();

        $category = create(Category::class, ['parent_id' => null]);

        $this->deleteJson(route('categories.destroy', $category['id']))
             ->assertStatus(403);

        $this->assertDatabaseHas('categories', $category->toArray());
    }

    /** @test */
    public function it_can_not_delete_if_unauthorized()
    {
        $this->putJson(route('categories.destroy', 1))
             ->assertStatus(401);
    }

    /** @test */
    public function it_can_delete_subcategory_if_has_permission()
    {
        $this->signIn();

        tap(auth()->user(), function (User $user) {
            $user->givePermissionTo(PermissionEnum::DELETE_CATEGORY);

            /** @var Category $category */
            $category = create(Category::class, ['parent_id' => null]);
            /** @var Category $subcategory */
            $subcategory = create(Category::class, ['parent_id' => $category->id]);

            $this->assertCount(1, $category->subcategories);

            $subcategory = $subcategory->toArray();
            $this->deleteJson(route('categories.destroy', $subcategory['id']))
                 ->assertStatus(204);

            $this->assertDatabaseMissing('categories', $subcategory);

            $this->assertCount(0, $category->fresh()->subcategories);
        });
    }
}
