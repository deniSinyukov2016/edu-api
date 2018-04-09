<?php

namespace App\Http\Requests\Lesson;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_LESSON);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'            => 'string|max:255',
            'description'     => 'nullable',
            'file'            => 'nullable',
            'type_lessons_id' => 'exists:type_lessons,id',
            'module_id'       => 'nullable|exists:modules,id',
            'course_id'       => 'exists:courses,id',
        ];
    }
}
