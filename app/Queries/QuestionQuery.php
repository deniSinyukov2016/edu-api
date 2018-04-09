<?php

namespace App\Queries;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

class QuestionQuery
{
    private static $TYPE_ANSWER   = [
        'ONE_ANSWER' => 1,
        'MULTI_ANSWER' => 2
    ];


    public static function questionSuccess(Collection $collection, array $request)
    {
        $questionsCorrect = $collection->filter(function (Question $question) use ($request) {

            $data = static::split($request[$question->id]);

            $answersCorrect = $question->answers()->whereIn('id', $data)->where(function ($answer) {
                $answer->where('is_correct', true);
            });

            /** Count correct answers not equals with count in request */
            if ($answersCorrect->count() != count($data)) {
                return false;
            }
            /** Type question one answer, but can has many answers in request */
            if ($question->type_answer_id == static::$TYPE_ANSWER['ONE_ANSWER']) {
                return $answersCorrect->count() == 1;
            }
            /** If count answers correct >= question min count correct, then question success */
            return $answersCorrect->count() >= $question->count_correct;
        })->values();

        return $questionsCorrect;
    }

    private static function split(string $data)
    {
        $str = str_replace(array('[', ']'), '', $data);
        $data = explode(",", $str);

        return $data;
    }
}
