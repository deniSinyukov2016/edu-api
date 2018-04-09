<?php

namespace App\Listeners;

class UpdateFiles
{
    public function handle($event)
    {
        if (!request()->exists('file') || !is_array($event->request->get('file'))) {
            return;
        }

        foreach ($event->request->get('file') as $key => $file) {
            if ($key == "null") {
                $event->lesson->files()->create(['file' => $file]);
                continue;
            }

            $event->lesson->files()->whereKey($key)->update([
                'file'          => $file,
                'type'          => null,
                'size'          => 0.0,
                'original_name' => ''
            ]);
        }
    }
}
