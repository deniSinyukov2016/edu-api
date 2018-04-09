<?php

use App\Models\CourseStatus;

class CourseStatusTableSeeder extends BaseSeeder
{

    public function run()
    {
        $courseStatuses = config('enum.course_statuses');

        foreach ($courseStatuses as $courseStatus) {
            CourseStatus::query()->create([
                'title' => $courseStatus['title']
            ]);
        }
    }
}
