<?php

namespace App\Providers;

use App\Models\Course;
use App\Observers\CourseObserver;
use Illuminate\Support\ServiceProvider;

class ItemModelServiceProvider extends ServiceProvider
{

    public function boot()
    {
//        Course::observe(CourseObserver::class);
    }

    public function register()
    {
        //
    }
}
