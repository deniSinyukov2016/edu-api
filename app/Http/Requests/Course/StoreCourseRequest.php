<?php

namespace App\Http\Requests\Course;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_COURSE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'              => 'required|string|max:255',
            'meta_keywords'      => 'required',
            'meta_description'   => 'required',
            'slug'               => 'required|string|min:5|unique:courses,slug',
            'body'               => 'nullable',
            'price'              => 'required|numeric',
            'duration'           => 'required|numeric',
            'status'             => 'boolean',
            'category_id'        => 'required|exists:categories,id',
            'image'              => 'image|mimes:jpeg,jpg,png,gif',
            'files'              => 'nullable',
            'files.*'            => 'file',
            'is_sertificate'     => 'nullable|boolean',
            'target_audiences'   => 'array',
            'target_audiences.*' => 'string',
        ];
    }
}
