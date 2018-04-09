<?php

use App\Models\Lesson;
use App\Models\User;
use App\Pivots\LessonUser;
use Faker\Generator as Faker;

$factory->define(LessonUser::class, function (Faker $faker) {
    return [
        'lesson_id'   => create(Lesson::class)->id,
        'user_id'     => create(User::class)->id,
        'is_close'    => true,
        'is_complete' => false,
    ];
});
