<?php

namespace App\Models;

class TargetAudience extends BaseModel
{
    protected $guarded = ['id'];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
