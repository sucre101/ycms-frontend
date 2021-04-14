<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Validator, DB, Hash, Mail, Illuminate\Support\Facades\Password;

class YcmsAuthController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:ycms', ['except' => ['login', 'register']]);
  }

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

    $result = $this->login($request);

    return response()->json(['success' => true, 'user' => $result]);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login()
  {
    $credentials = request(['email', 'password']);

    if (! $token = auth('ycms')->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(auth('ycms')->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth('api')->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return $this->respondWithToken(auth('ycms')->refresh());
  }

  /**
   * Get the token array structure.
   *
   * @param  string  $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token): \Illuminate\Http\JsonResponse
  {
    return response()->json([
      'access_token' => $token,
      'user' => $this->guard()->user(),
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ]);
  }

  public function guard() {
    return Auth::Guard('ycms');
  }
}

