<?php

namespace App\Http\Requests\Module;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreModuleRequest extends FormRequest
{

    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_MODULE);
    }


    public function rules()
    {
        return [
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|min:5|unique:modules,slug',
            'description' => 'nullable',
            'course_id'   => 'required|exists:courses,id'
        ];
    }
}
