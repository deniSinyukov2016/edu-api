<?php

use App\Models\TypeLesson;
use Illuminate\Database\Seeder;

class TypeLessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeLessons = config('enum.type_lessons');

        foreach ($typeLessons as $typeLesson) {
            TypeLesson::query()->create([
                'title' => $typeLesson['title'],
                'name'  => $typeLesson['name']
            ]);
        }
    }
}
