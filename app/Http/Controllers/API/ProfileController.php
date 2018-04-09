<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\EditUserProfileRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Show auth user
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 401 | Unauthorized .
     */
    public function show()
    {
        return response()->json(auth()->user());
    }

    /**
     * @param EditUserProfileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiParam string  $name in_query | User name
     * @apiParam string  $password in_query | New user password
     * @apiParam string  $old_password in_query | Old user password. Required, if password field exists
     * @apiParam string  $password_confirmation in_query | New user password confirmation. Required, if password field exists
     * @apiResp 200 | Whatever message is send from backend on success
     * @apiResp 422 | Validation failed .
     * @apiResp 401 | Unauthorized .
     */
    public function update(EditUserProfileRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($request->exists('password')) {
            return $this->updatePassword($user, $request);
        }

        $user->update($request->except('password', 'password_confirmation', 'old_password'));

        return response()->json($user);
    }

    /**
     * @param User $user
     * @param EditUserProfileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function updatePassword($user, $request)
    {

        $user->update([
            'password'  => $request->get('password'),
            'api_token' => str_random(60)
        ]);

        return response()->json($user);
    }
}
