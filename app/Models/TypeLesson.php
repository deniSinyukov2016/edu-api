<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id;
 * @property string title;
 * @property string name;
 * @property Lesson lesson
 */
class TypeLesson extends Model
{
    protected $table = 'type_lessons';
    protected $guarded = ['id'];

    public function lesson()
    {
        return $this->hasMany(Lesson::class, 'type_lessons_id');
    }
}
