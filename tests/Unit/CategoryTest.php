<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Exception;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    
    /** @var Category $category  */
    protected $category;
    
    public function setUp()
    {
        parent::setUp();

        $this->category = create(Category::class);
        
    }

    /** @test */
    public function if_parent_id_is_null_has_parent_return_false()
    {
        $this->assertFalse($this->category->hasParent());
    }

    /** @test */
    public function if_parent_id_not_null_has_parent_return_true()
    {
        /** @var Category $subcategory */
        $subcategory = create(Category::class, ['parent_id' => $this->category->id]);

        $this->assertTrue($subcategory->hasParent());
    }

    /** @test */
    public function it_category_can_has_many_subcategories()
    {
        create(Category::class, ['parent_id' => $this->category->id], 2);

        $this->assertCount(2, $this->category->fresh()->subcategories);
    }

    /** @test */
    public function it_subcategory_has_only_one_parent()
    {
        /** @var Category $subcategory */
        $subcategory = create(Category::class, ['parent_id' => $this->category->id]);

        $this->assertEquals($subcategory->parent->toArray(), $this->category->toArray());
    }

    /** @test */
    public function it_subcategory_can_not_has_subcategories()
    {
        $this->expectException(\Exception::class);

        /** @var Category $subcategory */
        $subcategory = create(Category::class, ['parent_id' => $this->category->id]);

        $subcategory->subcategories;
    }

    /** @test */
    public function it_category_can_not_has_parent()
    {
        $this->expectException(\Exception::class);

        $this->category->parent;
    }

    /** @test */
    public function if_category_has_course_can_not_add_subcategory()
    {
        $this->expectException(Exception::class);

        create(Course::class, ['category_id' => $this->category->id]);
        create(Category::class, ['parent_id' => $this->category->id]);
    }
}
