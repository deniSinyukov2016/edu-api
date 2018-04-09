<?php

namespace App\Http\Requests\Course;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_COURSE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'              => 'string|max:255',
            'meta_keywords'      => 'nullable',
            'meta_description'   => 'nullable',
            'slug'               => 'string|min:5|unique:courses,slug',
            'body'               => 'nullable',
            'price'              => 'numeric',
            'duration'           => 'numeric',
            'status'             => 'boolean',
            'category_id'        => 'nullable|exists:categories,id',
            'target_audiences'   => 'array',
            'target_audiences.*' => 'string',
        ];
    }
}
