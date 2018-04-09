<?php

namespace App\Models;

use App\Pivots\BasePivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property integer user_id
 * @property integer course_id
 * @property integer event_type_id
 * @property User users
 * @property Lesson lessons
 * @property Course courses
 * */
class Event extends BaseModel
{
    use SoftDeletes;

    protected $table = 'events';
    protected $guarded = ['id'];
    protected $whereArray = ['event_type_id', 'course_id', 'user_id'];
    protected $withField = ['course', 'user', 'typeEvent'];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function typeEvent()
    {
        return $this->belongsTo(TypeEvent::class);
    }
}
