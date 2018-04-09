<?php

namespace App\Http\Requests;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(PermissionEnum::UPDATE_USER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'string|max:255',
            'email'    => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
        ];
    }
}
