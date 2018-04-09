<?php

namespace App\Http\Requests\Question;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_QUESTION) && Gate::allows(PermissionEnum::CREATE_ANSWER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_answer_id'       => 'required|integer|exists:type_answers,id',
            'test_id'              => 'required|exists:tests,id',
            'text'                 => 'required|max:255',
            'count_correct'        => 'required|integer|min:0',
            'answers'              => 'required|array',
            'answers.*.title'      => 'required|max:255',
            'answers.*.is_correct' => 'required|boolean'
        ];
    }
}
