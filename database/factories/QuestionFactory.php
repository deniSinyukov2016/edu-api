<?php

use Faker\Generator as Faker;
use App\Models\Question;
use App\Models\TypeAnswer;
use App\Models\Test;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'type_answer_id'    => create(TypeAnswer::class)->id,
        'test_id'           => create(Test::class)->id,
        'text'              => $faker->text,
        'count_correct'     => $faker->randomDigitNotNull,
    ];
});
