<?php


use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseTableSeeder extends Seeder
{

    public function run()
    {
        $categories = make(Category::class, [], 1);
        $categories->each(function (Category $category) {
            $courses = create(Course::class, ['category_id' => $category->id], 1);
            $courses->each(function (Course $course) {
                $course->images()->create(['image' => '/images/pexels-photo-374016.jpeg']);
            });
        });
    }
}
