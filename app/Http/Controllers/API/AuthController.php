<?php

namespace App\Http\Controllers\API;

use App\AuthSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\User;
use Validator, DB, Hash, Mail, Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $credentials = $request->only('name', 'email', 'password');

    $rules = [
      'name' => 'required|max:255|unique:users',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:6',
    ];
    $validator = Validator::make($credentials, $rules);
    if ($validator->fails()) {
      return response()->json([
        'success'=> false, 'error'=> $validator->messages()
      ]);
    }

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    return $this->login($request);
  }

  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = request(['email', 'password']);
    if(!Auth::attempt($credentials))
      return response()->json(['message' => 'Unauthorized'], 401);

    $user = $request->user();
    // $user->tokens()->delete();
    $tokenObj = $user->createToken('Personal Access Token');
    $token = $tokenObj->accessToken;
    // if ($request->remember_me)
    //     $token->expires_at = Carbon::now()->addWeeks(1);
    // $tokenObj->save();

    // all good so return the token
    $expires_in = $tokenObj->token->expires_at;
    return compact('user', 'token', 'expires_in');
  }

  /**
   * Log out
   * Invalidate the token, so user cannot use it anymore
   * They have to relogin to get a new token
   *
   * @param Request $request
   */
  // TODO: Delete?
  public function logout(Request $request) {
    $request->user()->token()->revoke();
    return ['message' => 'Successfully logged out'];
  }

  /**
   * API Recover Password
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function recover(Request $request)
  {
    $user = User::where('email', $request->email)->first();
    if (!$user) {
      $error_message = "Your email address was not found.";
      return response()->json([
        'success' => false, 'error' => ['email'=> $error_message]
      ], 401);
    }

    try {
      Password::sendResetLink(
        $request->only('email'),
        function (Message $message)
        {
          $message->subject('Your Password Reset Link');
        }
      );
    } catch (\Exception $e) {
      //Return with error
      $error_message = $e->getMessage();
      return response()->json([
        'success' => false, 'error' => $error_message
      ], 401);
    }

    return response()->json([
      'success' => true,
      'data'=> [
        'message' => 'A reset email has been sent! Please check your email.'
      ]
    ]);
  }

}
