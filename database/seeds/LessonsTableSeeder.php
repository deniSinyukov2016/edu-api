<?php

use App\Models\Course;
use App\Models\TypeLesson;

class LessonsTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeLessons = TypeLesson::all()->pluck('id')->toArray();
        /** @var \Illuminate\Support\Collection $courses */
        $courses = Course::all();

        $courses->each(function (Course $course) use ($typeLessons) {
            $data  = [];
            $count = random_int(5, 10);
            for ($i = 0; $i < $count; $i++) {
                $data[] = [
                    'name'            => $this->faker->sentence,
                    'description'     => $this->faker->paragraph,
                    'type_lessons_id' => $this->faker->randomElement($typeLessons),
                ];
            }
            $course->lessons()->createMany($data);
        });
    }
}
