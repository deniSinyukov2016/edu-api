<?php


use App\Models\TypeEvent;

class EventTypeTableSeeder extends BaseSeeder
{

    public function run()
    {
        $typeEvents = config('enum.type_events');

        foreach ($typeEvents as $typeEvent) {
            TypeEvent::query()->create([
                'title' => $typeEvent['title']
            ]);
        }
    }
}
