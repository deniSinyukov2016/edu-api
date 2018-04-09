<?php

namespace App\Http\Requests;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_CATEGORY);
    }

    public function rules()
    {
        return [
            'name'      => 'string|min:5',
            'slug'      => 'string|min:5|unique:categories,slug,' . $this->route('category')->id,
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }
}
