<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Confirmation;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Get user info by credentials
     * @apiParam string $email in_query required| User email
     * @apiParam string $password in_query required| User password
     * @apiErr 422 | Validation failed
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function handle(Request $request)
    {
        return $this->login($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return response()->json($this->guard()->user());
    }
}
