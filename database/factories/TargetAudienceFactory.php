<?php

use App\Models\TargetAudience;
use Faker\Generator as Faker;

$factory->define(TargetAudience::class, function (Faker $faker) {

    return [
        'title' => $faker->sentence
    ];
});
