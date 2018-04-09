<?php

namespace App;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'courses');
    }
}
