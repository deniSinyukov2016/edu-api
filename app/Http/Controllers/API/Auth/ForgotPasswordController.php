<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @apiDesc Send mail to enter email
     * @apiParam string $email in_query required| Email, where will get mail with link
     * @apiErr 500 | Server error
     * @apiErr 422 | Validation failed
     * @apiResp 200 | Whatever message is send from backend on success
     */
    public function handle()
    {
        return $this->sendResetLinkEmail(request());
    }

    protected function sendResetLinkResponse($response)
    {
        return response()->json(['status' => trans($response)]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['email' => trans($response)], 422);
    }
}
