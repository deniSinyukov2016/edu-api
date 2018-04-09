<?php

namespace App\Http\Requests\Question;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_QUESTION) && Gate::allows(PermissionEnum::UPDATE_ANSWER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_answer_id'       => 'integer|exists:type_answers,id',
            'test_id'              => 'exists:tests,id',
            'text'                 => 'max:255',
            'count_correct'        => 'integer|min:1',
            'answers'              => 'array',
            'answers.*.title'      => 'required|max:255',
            'answers.*.is_correct' => 'required|boolean'
        ];
    }
}
