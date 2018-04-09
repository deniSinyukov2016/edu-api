<?php

use App\Models\CourseStatus;
use Faker\Generator as Faker;

$factory->define(CourseStatus::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence
    ];
});
