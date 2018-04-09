<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name'      => $name = $faker->sentence(),
        'slug'      => str_slug($name),
        'parent_id' => null
    ];
});
