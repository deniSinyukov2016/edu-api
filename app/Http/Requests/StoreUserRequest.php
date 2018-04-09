<?php

namespace App\Http\Requests;

use App\Enum\PermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreUserRequest extends FormRequest
{

    public function authorize()
    {
        return Gate::allows(PermissionEnum::CREATE_USER);
    }


    public function rules()
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'avatar'   => 'image|mimes:jpeg,jpg,png,gif',
        ];
    }
}
