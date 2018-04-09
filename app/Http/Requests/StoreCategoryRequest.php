<?php

namespace App\Http\Requests;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_CATEGORY);
    }

    public function rules()
    {
        return [
            'name'      => 'required|string|min:5',
            'slug'      => 'required|string|min:5|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }
}
