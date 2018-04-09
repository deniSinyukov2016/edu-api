<?php

use Faker\Generator as Faker;
use App\Models\Test;
use App\Models\Lesson;

$factory->define(Test::class, function (Faker $faker) {
    return [
        'time_passing'  => $faker->time(),
        'is_random'     => false,
        'count_attemps' => $faker->randomDigitNotNull,
        'count_correct' => $faker->randomDigitNotNull,
        'lesson_id'     => create(Lesson::class)->id,
        'is_require'    => $faker->boolean,
        'is_success'    => $faker->boolean
    ];
});
