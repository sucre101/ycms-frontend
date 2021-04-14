<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        if ($request->isJson())
            return ['errors' => ['email' => [trans($response)]]];
        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        if ($request->isJson()) return ['success' => trans($response)];
        return back()->with('status', trans($response));
    }

    protected function validateEmail(Request $request)
    {
        $rules = ['email' => 'required|email'];

        if ($request->isJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return ['errors' => $validator->errors()];
        }

        $request->validate($rules);
    }
}
