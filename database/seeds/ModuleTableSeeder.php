<?php

use App\Models\Course;

class ModuleTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Course $courses */
        $courses = Course::query()->take(10)->get();

        $courses->each(function (Course $course) {
            $title = $this->faker->sentence;

            $course->modules()->create([
                'title'       => $title,
                'slug'        => str_slug($title),
                'description' => $this->faker->paragraph,
            ]);
        });
    }
}
