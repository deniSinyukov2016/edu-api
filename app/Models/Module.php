<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * @property Course course
 * @property Lesson lessons
 * @property int id;
 * @property int course_id;
 * @property string title;
 * @property string slug;
 * @property string description;
 */
class Module extends BaseModel
{
    protected $guarded        = ['id'];
    protected $whereArray     = ['course_id'];
    protected $whereLikeArray = ['title', 'description'];
    protected $whereInArray   = ['id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'module_id');
    }

    /**
     * If course does not exist throw ModelNotFoundException
     *
     * @param $value
     *
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function setCourseIdAttribute($value)
    {
        $query = Course::query()->where('id', $value);

        if (!$query->exists()) {
            throw new ModelNotFoundException('Course does not exist');
        }
        //if ($query->first()->lessons()->count() > 0) {
        //    throw new Exception('Course has lessons');
        //}

        $this->attributes['course_id'] = $value;
    }
}
