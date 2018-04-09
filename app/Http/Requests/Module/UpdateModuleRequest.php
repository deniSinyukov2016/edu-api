<?php

namespace App\Http\Requests\Module;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_MODULE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'string|max:255',
            'slug'        => 'string|min:5|unique:modules,slug',
            'description' => 'nullable',
            'course_id'   => 'exists:courses,id'
        ];
    }
}
