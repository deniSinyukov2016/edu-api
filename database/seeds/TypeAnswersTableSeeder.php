<?php

use App\Models\TypeAnswer;
use Illuminate\Database\Seeder;

class TypeAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeAnswers = config('enum.type_answers');

        foreach ($typeAnswers as $typeAnswer) {
            TypeAnswer::query()->create([
                'title' => $typeAnswer['title'],
            ]);
        }
    }
}
