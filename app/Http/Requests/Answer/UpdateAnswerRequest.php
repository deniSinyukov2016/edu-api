<?php

namespace App\Http\Requests\Answer;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_ANSWER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'max:255',
            'question_id' => 'integer|exists:questions,id',
            'is_correct'  => 'boolean'
        ];
    }
}
