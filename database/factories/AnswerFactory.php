<?php

use Faker\Generator as Faker;
use App\Models\Answer;
use App\Models\Question;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'title'         => $faker->sentence(),
        'question_id'   => create(Question::class)->id,
        'is_correct'    => $faker->boolean
    ];
});
