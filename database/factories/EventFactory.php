<?php

use App\Models\Course;
use App\Models\Event;
use App\Models\TypeEvent;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'user_id'       => create(User::class),
        'course_id'     => create(Course::class),
        'event_type_id' => create(TypeEvent::class),
    ];
});

$factory->define(TypeEvent::class, function (Faker $faker) {
    return [
        'title'   => $faker->sentence
    ];
});
