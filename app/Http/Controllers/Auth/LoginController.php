<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  public function login(Request $request)
  {
      if ($errors = $this->validateLogin($request)) {
        return $errors;
      }

      // If the class is using the ThrottlesLogins trait, we can automatically throttle
      // the login attempts for this application. We'll key this by the username and
      // the IP address of the client making these requests into this application.
      if (method_exists($this, 'hasTooManyLoginAttempts') &&
          $this->hasTooManyLoginAttempts($request)) {
          $this->fireLockoutEvent($request);

          return $this->sendLockoutResponse($request);
      }

      if ($this->attemptLogin($request)) {
          return $this->sendLoginResponse($request);
      }

      // If the login attempt was unsuccessful we will increment the number of attempts
      // to login and redirect the user back to the login form. Of course, when this
      // user surpasses their maximum number of attempts they will get locked out.
      $this->incrementLoginAttempts($request);

      return $this->sendFailedLoginResponse($request);
  }

  protected function validateLogin(Request $request)
  {
    $rules = [
      $this->username() => 'required|string',
      'password' => 'required|string',
    ];

    if (!$request->isJson()) {
      $request->validate($rules);
    } else {
      $validator = Validator::make($request->only('email', 'password'), $rules);
      if ($validator->fails())
        return ['errors' => $validator->errors() /*->all()*/];
    }
  }

  protected function sendFailedLoginResponse(Request $request)
  {
    if (!$request->isJson()) throw ValidationException::withMessages([
      $this->username() => [trans('auth.failed')],
    ]);
    else return [
      'errors' => ValidationException::withMessages([
        $this->username() => [trans('auth.failed')],
      ])->errors()
    ];
  }
}
