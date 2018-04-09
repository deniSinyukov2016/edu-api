<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(TypeAnswersTableSeeder::class);
        $this->call(EventTypeTableSeeder::class);

        if (config('app.env') !== 'production') {
            $this->call(CategoryTableSeeder::class);
            $this->call(CourseStatusTableSeeder::class);
            $this->call(CourseUserTableSeeder::class);
            $this->call(LessonsTableSeeder::class);
            $this->call(TestsTableSeeder::class);
            $this->call(QuestionTableSeeder::class);
            $this->call(AnswersTableSeeder::class);
            $this->call(EventTableSeeder::class);
        }
    }
}
