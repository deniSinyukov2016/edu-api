<?php

namespace Tests\Unit;

use App\Exceptions\CourseAcceptException;
use App\Models\Category;
use App\Models\Course;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_course_can_not_be_added_if_category_has_subcategories_throw_exception()
    {
        $this->expectException(Exception::class);

        $category = create(Category::class);
        // create subcategory
        create(Category::class, ['parent_id' => $category->id]);
        create(Course::class, ['category_id' => $category->id]);
    }

    /** @test */
    public function it_course_can_be_added_in_subcategory()
    {
        $category = create(Category::class);
        $subcategory = create(Category::class, ['parent_id' => $category->id]);

        /** @var Course $course */
        $course = create(Course::class, ['category_id' => $subcategory->id]);

        $this->assertDatabaseHas('courses', $course->toArray());
        
    }

    /** @test */
    public function it_course_can_be_added_to_category()
    {
        $category = create(Category::class);

        /** @var Course $course */
        $course = create(Course::class, ['category_id' => $category->id]);

        $this->assertDatabaseHas('courses', $course->toArray());
    }

    /** @test
     * @throws CourseAcceptException
     */
    public function it_user_can_not_accept_course_if_course_accepted_throw_exception()
    {
        \Event::fake();

        $this->signIn();
        /** @var Course $course */
        $course = create(Course::class);
        $course->attachUsers([auth()->id()]);

        $this->expectException(CourseAcceptException::class);
        $course->attachUsers([auth()->id()]);
    }
}
