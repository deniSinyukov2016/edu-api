<?php

use App\Enum\EventEnum;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class CourseUserTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \Illuminate\Support\Collection $courses */
        $courses = Course::all();
        $query = User::query();

        $courses->each(function (Course $course) use ($query) {
            $course->courseUser()->create([
                'user_id'          => $this->faker->randomElement($query->take(5)->pluck('id')->toArray()),
                'start_time'       => Carbon::now(),
                'close_time'       => Carbon::now()->addDay(2),
                'course_status_id' => EventEnum::START_COURSE
            ]);
        });

        $courses->each(function (Course $course) use ($query) {
            $course->courseUser()->create([
                'user_id'          => $this->faker->randomElement($query->skip(5)->take(5)->pluck('id')->toArray()),
                'start_time'       => Carbon::now(),
                'close_time'       => Carbon::now()->addDay(-2),
                'course_status_id' => EventEnum::FINISH_COURSE
            ]);
        });
    }
}
