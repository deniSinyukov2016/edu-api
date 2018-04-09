<?php

use App\Models\Test;
use App\Models\TypeAnswer;

class QuestionTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeAnswers = TypeAnswer::all()->pluck('id')->toArray();
        /** @var \Illuminate\Support\Collection $tests */
        $tests = Test::all();

        $tests->each(function (Test $test) use ($typeAnswers) {
            $data  = [];
            $count = random_int(8, 24);
            for ($i = 0; $i < $count; $i++) {
                $data[] = [
                    'type_answer_id' => $this->faker->randomElement($typeAnswers),
                    'text'           => $this->faker->text,
                    'count_correct'  => $this->faker->randomDigitNotNull,
                ];
            }
            $test->questions()->createMany($data);
        });
    }
}
