<?php


use App\Models\Course;
use App\Models\TypeEvent;

class EventTableSeeder extends BaseSeeder
{
    public function run()
    {
        $typeEvents = TypeEvent::all()->pluck('id')->toArray();
        /** @var Course $courses */
        $courses = Course::query()->take(20)->get();

        $courses->each(function (Course $course) use ($typeEvents) {
            $course->events()->create([
                'user_id'       => $this->getFaker()->randomElement($this->getUsers()),
                'event_type_id' => $this->faker->randomElement($typeEvents),
            ]);
        });
    }
}
