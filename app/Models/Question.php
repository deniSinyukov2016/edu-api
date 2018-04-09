<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @property int id;
 * @property int type_answer_id;
 * @property int test_id;
 * @property string text;
 * @property int count_correct;
 * @property Test test
 * @property TypeAnswer typeAnswer
 * @property Answer answers
 */
class Question extends BaseModel
{
    protected $guarded = ['id'];
    protected $whereArray = ['type_answer_id', 'test_id'];
    protected $whereLikeArray = ['text'];
    protected $whereInArray = ['id'];
    protected $whereBetweenField = 'count_correct';
    protected $withField = ['answers', 'test', 'typeAnswer'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function typeAnswer()
    {
        return $this->hasOne(TypeAnswer::class, 'type_answer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    /**
     * If type answer not exist throw Exception
     *
     * @param $value
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function setTypeAnswerIdAttribute($value)
    {
        if (!isset($value) || !TypeAnswer::query()->whereKey($value)->exists()) {
            throw new ModelNotFoundException('Type answer not found');
        }
        $this->attributes['type_answer_id'] = $value;
    }

    /**
     * If test not exist throw Exception
     *
     * @param $value
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function setTestIdAttribute($value)
    {
        if (!isset($value) || !Test::query()->whereKey($value)->exists()) {
            throw new ModelNotFoundException('Test not found');
        }
        $this->attributes['test_id'] = $value;
    }

    public function addAnswers(array $answers)
    {
        $this->answers()->createMany($answers);
    }
}
