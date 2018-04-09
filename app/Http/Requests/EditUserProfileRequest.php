<?php

namespace App\Http\Requests;

use App\Rules\CheckOldAndPasswordRule;
use App\Rules\CheckOldPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class EditUserProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'string|max:255',
            'old_password' => ['string','min:6',new CheckOldPasswordRule, new CheckOldAndPasswordRule],
            'password'     => 'string|min:6|confirmed',
        ];
    }
}
