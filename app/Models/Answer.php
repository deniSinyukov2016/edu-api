<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @property int id
 * @property string title
 * @property int question_id
 * @property Question question
 */
class Answer extends BaseModel
{
    protected $guarded = ['id'];
    protected $whereArray = ['question_id'];
    protected $whereLikeArray = ['title'];
    protected $whereInArray = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * If question does exist throw Exception
     *
     * @param $value
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function setQuestionIdAttribute($value)
    {
        if (!isset($value) || !Question::query()->whereKey($value)->exists()) {
            throw new ModelNotFoundException('Question not found');
        }
        $this->attributes['question_id'] = $value;
    }

    /**
     * @param Boolean $value
     */
    public function setIsCorrectAttribute($value)
    {
        $this->attributes['is_correct'] = (bool)$value;
    }
}
