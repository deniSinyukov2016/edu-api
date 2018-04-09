<?php

use App\Models\Question;

class AnswersTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \Illuminate\Support\Collection $question */
        $question = Question::all();

        $question->each(function (Question $question) {
            $data  = [];
            $count = random_int(3, 6);
            for ($i = 0; $i < $count; $i++) {
                $data[] = [
                    'title'       => $this->faker->sentence(),
                    'is_correct'  => $this->faker->boolean
                ];
            }

            $question->answers()->createMany($data);
            $question->update([
                'count_correct' => $question->answers()->where(['is_correct' => true])->count()
            ]);
        });
    }
}
