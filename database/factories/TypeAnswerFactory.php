<?php

use App\Models\TypeAnswer;
use Faker\Generator as Faker;

$factory->define(TypeAnswer::class, function (Faker $faker) {
    return [
        'title'    => $faker->jobTitle
    ];
});
