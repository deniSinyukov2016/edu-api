<?php

namespace App\Http\Requests\Test;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_TEST);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'time_passing'  => 'nullable|date_format:H:i:s',
            'is_random'     => 'nullable|boolean',
            'count_attemps' => 'nullable|integer|min:1',
            'count_correct' => 'nullable|integer|min:1',
            'lesson_id'     => 'nullable|exists:lessons,id',
            'is_require'    => 'nullable|boolean',
        ];
    }
}
