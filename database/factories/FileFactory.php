<?php

use App\Models\Course;
use Faker\Generator as Faker;
use App\Models\File;

$factory->define(File::class, function (Faker $faker) {
    return [
        'fileable_id'       => create(Course::class)->id,
        'fileable_type'     => Course::class,
        'file'              => $faker->url,
        'type'              => $faker->mimeType,
        'size'              => $faker->randomElement([25, 50, 60, 70]),
        'original_name'     => $faker->sentence,
        'is_sertificate'    => $faker->boolean
    ];
});
