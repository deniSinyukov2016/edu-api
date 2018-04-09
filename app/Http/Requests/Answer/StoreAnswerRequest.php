<?php

namespace App\Http\Requests\Answer;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_ANSWER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|max:255',
            'question_id' => 'required|integer|exists:questions,id',
            'is_correct'  => 'nullable|boolean'
        ];
    }
}
