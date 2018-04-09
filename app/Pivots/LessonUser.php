<?php

namespace App\Pivots;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property integer lesson_id
 * @property integer user_id
 * @property boolean status
 * @property User user
 * @property Lesson course
 */
class LessonUser extends Pivot
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function getCreatedAtColumn()
    {
        return 'created_at';
    }

    public function getUpdatedAtColumn()
    {
        return 'updated_at';
    }
}