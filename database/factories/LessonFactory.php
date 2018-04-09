<?php

use Faker\Generator as Faker;
use App\Models\Lesson;
use App\Models\TypeLesson;
use App\Models\Course;
use Illuminate\Support\Str;

$factory->define(Lesson::class, function (Faker $faker) {

    /** @var Course $course  */
    $course = create(Course::class);
    
    return [
        'name'              => $faker->title,
        'description'       => $faker->paragraph,
        'type_lessons_id'   => $faker->randomElement(TypeLesson::query()->pluck('id')->toArray()),
        'course_id'         => $course->id
    ];
});

$factory->define(TypeLesson::class, function (Faker $faker) {

    $title = $faker->sentence;
    $slug = Str::slug($title);

    return [
        'title' => $title,
        'name'  => $slug
    ];
});
