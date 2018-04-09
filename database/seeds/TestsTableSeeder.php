<?php

use App\Enum\LessonTypeEnum;
use App\Models\Lesson;

class TestsTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lessons = Lesson::query()->where('type_lessons_id', LessonTypeEnum::LESSON_TYPE_TEST)->get();

        $lessons->each(function (Lesson $lesson) {
            $data  = [];
            $count = random_int(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $data[] = [
                    'is_require'    => $this->faker->boolean,
                    'time_passing'  => $this->faker->time(),
                    'is_random'     => $this->faker->boolean,
                    'count_attemps' => $this->faker->randomDigitNotNull,
                    'count_correct' => $this->faker->randomDigitNotNull,
                    'is_success'    => $this->faker->boolean,
                ];
            }
            $lesson->test()->createMany($data);
        });
    }
}
