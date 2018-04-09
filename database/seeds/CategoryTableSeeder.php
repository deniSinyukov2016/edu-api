<?php

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /** @var Faker\Generator */
    protected $faker;

    public function run()
    {
        $this->faker = Faker\Factory::create();

        for ($i = 0; $i < 2; $i++) {
            $this->category();
        }

        $this->subcategories();
        $this->course();
    }

    public function category()
    {
        $name = ucfirst($this->faker->words(3, true));

        return Category::query()->create([
            'name'      => $name,
            'slug'      => str_slug($name),
            'parent_id' => null
        ]);
    }

    private function subcategories()
    {
        Category::all()->each(function (Category $category) {
            $count = random_int(2, 8);
            for ($j = 0; $j <= $count; $j++) {
                $name = ucfirst($this->faker->words(3, true));

                /** @var Category $category */
                $subcategory = Category::query()->create([
                    'name'      => $name,
                    'slug'      => str_slug($name),
                    'parent_id' => $category->id
                ]);

                $subcategory->images()->create(['image' => '/images/pexels-photo-267885.jpeg']);
            }
        });
    }

    public function course()
    {
        Category::query()->whereNotNull('parent_id')->get()->each(function (Category $subcategory) {
            $count = random_int(3, 6);

            for ($j = 0; $j <= $count; $j++) {
                /** @var Course $course */
                $course = $subcategory->courses()->create([
                    'title'       => $title = $this->faker->sentence,
                    'slug'        => str_slug($title),
                    'body'        => $this->faker->text(2000),
                    'price'       => random_int(1000, 10000),
                    'duration'    => random_int(1, 10),
                ]);

                $course->images()->create(['image' => '/images/pexels-photo-374016.jpeg']);

                for ($i = 0; $i <= 5; $i++) {
                    $course->targetAudiences()->create(['title' => $this->faker->sentence()]);
                }

                //for ($i = 0; $i <= 8; $i++) {
                //    $course->lessons()->create([
                //        'name'       => $title = $this->faker->sentence(),
                //        'description' => $this->faker->text(400)
                //    ]);
                //}


            }
        });
    }
}
