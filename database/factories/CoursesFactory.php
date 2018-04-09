<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Category;

$factory->define(Course::class, function (Faker $faker) {

    $title = $faker->sentence;
    $slug = Str::slug($title);

    return [
        'title'             => $title,
        'meta_keywords'     => $title,
        'meta_description'  => $title,
        'slug'              => $slug,
        'body'              => $faker->paragraph(),
        'price'             => random_int(1000, 20000),
        'duration'          => $faker->randomDigitNotNull(),
        'category_id'       => create(Category::class)->id
    ];
});
