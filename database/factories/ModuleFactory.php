<?php

use Faker\Generator as Faker;
use App\Models\Course;
use App\Models\Module;

$factory->define(Module::class, function (Faker $faker) {

    $title = $faker->sentence;
    $slug = str_slug($title);

    return [
        'title'         => $title,
        'slug'          => $slug,
        'description'   => $faker->paragraph(),
        'course_id'     => create(Course::class)->id
    ];
});
