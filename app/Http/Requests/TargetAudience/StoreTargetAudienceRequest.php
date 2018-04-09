<?php

namespace App\Http\Requests\TargetAudience;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreTargetAudienceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_TARGET);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required'
        ];
    }
}
