<?php

namespace App\Http\Requests\Test;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_TEST);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'time_passing'  => 'required|date_format:H:i:s',
            'is_random'     => 'nullable|boolean',
            'count_attemps' => 'required|integer|min:1',
            'count_correct' => 'required|integer|min:1',
            'lesson_id'     => 'required|exists:lessons,id',
            'is_require'    => 'nullable|boolean',
        ];
    }
}
