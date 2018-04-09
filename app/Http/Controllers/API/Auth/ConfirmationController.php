<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;

class ConfirmationController extends Controller
{
    /**
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @apiDesc Confirm user and make possible use site or admin functionality
     * @apiParam string $token in_path required| Confirmation token
     * @apiErr 500 | Server error
     * @apiResp 302 | Redirect to frontend index page
     */
    public function handle(string $token)
    {
        User::query()->where(['is_confirm' => false, 'api_token' => $token])
            ->update([
                'is_confirm'     => true,
                'api_token'      => str_random(60),
                'remember_token' => null
            ]);

        return redirect()->to(url(config('app.frontend_url')));
    }

    /**
     * @apiDesc Return email on token
     * @apiParam string $token in_path required| Confirmation token
     * @apiErr  500 | Server error
     * @apiResp 200 | Successfully or false
     *z
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $token)
    {
        return response()->json(User::emailToken($token));
    }
}
