<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Reset user password
     * @apiParam string $email in_query required| Email, where will get mail with link
     * @apiParam string $token in_query required| Token, which will into mail letter
     * @apiParam string $password in_query required| New password
     * @apiParam string $password_confirmation in_query required| New password confirm
     * @apiErr 500 | Server error
     * @apiErr 422 | Validation failed
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function handle()
    {
        return $this->reset(request());
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword|User $user
     * @param  string $password
     *
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    protected function sendResetResponse($response)
    {
        return response()->json(['status' => trans($response)]);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['email' => trans($response)], 422);
    }
}
