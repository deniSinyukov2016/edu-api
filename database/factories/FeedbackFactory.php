<?php

use Faker\Generator as Faker;
use App\Models\Feedback;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'name'    => $faker->name,
        'email'   => $faker->email,
        'message' => $faker->paragraph,
        'phone'   => $faker->phoneNumber
    ];
});
