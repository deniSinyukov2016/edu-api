<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'users'   => 'required|array',
            'users.*' => 'numeric'
        ];
    }
}
