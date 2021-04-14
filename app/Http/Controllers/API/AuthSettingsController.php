<?php

namespace App\Http\Controllers\API;

use App\App;
use App\AppUser;
use App\AppUserToken;
use App\AuthSetting;
use App\Mail\SendRecoveryEmail;
use App\Mail\SendTokenEmail;
use App\Services\Registration\RegistrationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Mail;
use Nexmo\Laravel\Facade\Nexmo;
use Hash;
use Validator;

class AuthSettingsController extends Controller
{

  /**
   * API Auth settings
   *
   * @param $app_id
   * @return JsonResponse
   */
  public function recipe($app_id): JsonResponse
  {
    $settings = AuthSetting::where('app_id', $app_id)->first();

    if ( !$settings ) {
      return response()->json(['error' => 'application not found']);
    }

    return response()->json([
      "base_url" =>  url('/'),
      "app_id" => $app_id,
      "auth" => $settings,
    ]);
  }

  /**
   * API register new app user
   *
   * @param $app_id
   * @param  Request  $request
   * @return JsonResponse
   * @throws Exception
   */
  public function registration($app_id, Request $request)
  {
    $registerUser = (new RegistrationService($request))->init($app_id);

    $type = "";
    $phone = "";
    $email = "";
    $now = Carbon::now();

    //find app
    $app = App::whereId($app_id)->with('auth_settings')->first();
    if(!$app){
      return response()->json([
        'success'=> false, 'message'=> "App not found"
      ]);
    }

    $user = AppUser::where([
      "app_id" => $app_id
    ]);

    if ( isset($request->email) ) {

      $email = $request->email;

      $debounce = $app->auth_settings->email_debounce;
      $debounce_ = explode(":", $debounce);
      $now->addHours($debounce_[0]);
      $now->addMinutes($debounce_[1]);
      $now->addSeconds($debounce_[2]);

      $user = $user->where([
        "email" => $request->email
      ]);

    }

    if ( isset($request->phone) ){

      $phone = str_replace("+","", $request->phone);

      $debounce = $app->auth_settings->phone_debounce;
      $debounce_ = explode(":", $debounce);
      $now->addMinutes($debounce_[0]);
      $now->addSeconds($debounce_[1]);

      $user = $user->where([
        "phone" => $phone
      ]);

    }

    $user = $user->first();

    if ( $user ) {
      return response()->json([
        "success" => false,
        "message" => "You have already registered"
      ]);
    }

    if ( isset($request->email) ) {

      $type = "email";
      //validation
      $credentials = $request->only('name','email', 'password');

      $rules = [
        'name' => 'required|string',
        'email' => 'required|email|max:255|unique:app_users',
        'password' => 'required|min:6',
      ];

    }

    if ( isset($request->phone) ) {

      $type = "phone";
      //validate
      $credentials = $request->only('name', 'phone', 'password');

      $rules = [
        'name' => 'required|string',
        'phone' => 'required|string|max:255|unique:app_users',
        'password' => 'required|min:6',
      ];

    }

    $validator = Validator::make($credentials, $rules);

    if ($validator->fails()) {
      return response()->json([
        'success'=> false, 'message'=> $validator->messages()
      ]);
    }

    $user = AppUser::create([
      'app_id' => $app_id,
      'name' => $request->name,
      'email' => $email,
      'phone' => $phone,
      'status' => "new",
      'password' => Hash::make($request->password)
    ]);

    if ( isset($request->email) ) {

      //generate token
      $token = random_bytes(10);
      $token = bin2hex($token);
      //send mail
      Mail::to($request->email)
        ->send(new SendTokenEmail($app_id, $request->email, $token));
    }

    if ( isset($request->phone) ) {

      //generate token
      $token = random_int(100000, 999999);
      //send sms
      Nexmo::message()->send([
        'to'   => $request->phone,
        'from' => 'Yappix',
        'text' => "Verification code: $token"
      ]);

    }

    //save token
    $userToken = AppUserToken::create([
      "app_user_id" => $user->id,
      "type" => $type,
      "token" => $token,
      "status" => "sent",
      "sent_at" => Carbon::now(),
      "expires_at" => $now
    ]);

    return response()->json([
      "success" => true,
      "message" => 'Successfully sent',
      "sent_at" => $userToken->sent_at,
      "expires_at" => $userToken->expires_at,
      "debounce" => $debounce,
    ]);

  }


  /**
   * API resend code
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function resend($app_id, Request $request)
  {
    $type = "";
    $phone = "";
    $email = "";
    $now = Carbon::now();
    //find app
    $app = App::whereId($app_id)->with('auth_settings')->first();
    if(!$app){
      return response()->json([
        'success'=> false, 'message'=> "App not found"
      ]);
    }
    $user = AppUser::where([
      "app_id" => $app_id
    ]);
    if(isset($request->email)){
      $type = "email";
      $email = $request->email;

      $debounce = $app->auth_settings->email_debounce;
      $debounce_ = explode(":", $debounce);
      $now->addHours($debounce_[0]);
      $now->addMinutes($debounce_[1]);
      $now->addSeconds($debounce_[2]);

      $user = $user->where([
        "email" => $request->email
      ]);
    }
    if(isset($request->phone)){
      $type = "phone";
      $phone = str_replace("+","", $request->phone);

      $debounce = $app->auth_settings->phone_debounce;
      $debounce_ = explode(":", $debounce);
      $now->addMinutes($debounce_[0]);
      $now->addSeconds($debounce_[1]);

      $user = $user->where([
        "phone" => $phone
      ]);
    }
    $user = $user->first();

    if(!$user){
      return response()->json([
        "success" => false,
        "message" => "User not found"
      ]);
    }

    if(isset($request->email)) {
      //generate token
      $token = random_bytes(10);
      $token = bin2hex($token);
      //send mail
      Mail::to($request->email)
        ->send(new SendTokenEmail($app_id, $request->email, $token));
    }

    if(isset($request->phone)){
      //generate token
      $token = random_int(100000, 999999);
      //send sms
      Nexmo::message()->send([
        'to'   => $request->phone,
        'from' => 'Yappix',
        'text' => "Verification code: $token"
      ]);
    }

    //save token
    $userToken = AppUserToken::create([
      "app_user_id" => $user->id,
      "type" => $type,
      "token" => $token,
      "status" => "sent",
      "sent_at" => Carbon::now(),
      "expires_at" => $now
    ]);
    return response()->json([
      "success" => true,
      "message" => 'Successfully sent',
      "sent_at" => $userToken->sent_at,
      "expires_at" => $userToken->expires_at,
      "debounce" => $debounce,
    ]);
  }

  /**
   * API token confirmation
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function confirmation($app_id, Request $request)
  {
    //find app
    $app = App::whereId($app_id)->with('auth_settings')->first();
    if(!$app){
      return response()->json([
        'success'=> false, 'message'=> "App not found"
      ]);
    }
    //find app user
    $user = AppUser::where([
      "app_id" => $app_id
    ]);
    if(isset($request->email)){
      $user = $user->where([
        "email" => $request->email
      ]);
    }
    if(isset($request->phone)){
      $user = $user->where([
        "phone" => $request->phone
      ]);
    }
    $user = $user->first();
    if (isset($user) && $user){
      //find token
      $token = AppUserToken::where([
        "app_user_id" => $user->id,
        "token" => $request->token,
        "status" => "sent",
      ])->first();

      if($token){
        //change statuses
        $token->update([
          "status" => "confirmed"
        ]);

        $user->update([
          "status" => "confirmed"
        ]);
        return response()->json([
          "success" => true,
          "message" => 'Successfully confirmed',
          "sent_at" => '',
          "expires_at" => '',
          "debounce" => '',
        ]);
      }
    }

    return response()->json([
      "success" => false,
      "message" => 'No data found',
      "sent_at" => '',
      "expires_at" => '',
      "debounce" => '',
    ]);
  }

  /**
   * API user login
   *
   * @param $app_id
   * @param  Request  $request
   * @return JsonResponse
   */
  public function login($app_id, Request $request): JsonResponse
  {

    if ( isset($request->email) ) {
      $credentials = $request->only('email', 'password');
    }

    if ( isset($request->phone) && !isset($request->email) ) {
      $credentials = $request->only('phone', 'password');
    }

    $credentials["app_id"] = $app_id;

    if (! $token = auth('api')->attempt($credentials) ) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }

  /**
   * Get the token array structure.
   *
   * @param  string  $token
   *
   * @return JsonResponse
   */
  protected function respondWithToken(string $token): JsonResponse
  {
    $user = Auth::Guard('api')->user()->first();

    return response()->json([
      'access_token' => $token,
      'user' => $user,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ]);
  }

  public function guard() {
    return Auth::Guard('api');
  }

  /**
   * API send code for recovery password
   *
   * @param  Request  $request
   * @return JsonResponse
   * @throws Exception
   */
  public function recovery($app_id, Request $request)
  {
    $type = "";
    $phone = "";
    $email = "";
    $now = Carbon::now();
    //find app
    $app = App::whereId($app_id)->with('auth_settings')->first();
    if(!$app){
      return response()->json([
        'success'=> false, 'message'=> "App not found"
      ]);
    }
    $user = AppUser::where([
      "app_id" => $app_id
    ]);
    if(isset($request->email)){
      $type = "recovery";
      $email = $request->email;

      $debounce = $app->auth_settings->email_debounce;
      $debounce_ = explode(":", $debounce);
      $now->addHours($debounce_[0]);
      $now->addMinutes($debounce_[1]);
      $now->addSeconds($debounce_[2]);

      $user = $user->where([
        "email" => $request->email
      ]);
    }
    if(isset($request->phone)){
      $type = "recovery";
      $phone = str_replace("+","", $request->phone);

      $debounce = $app->auth_settings->phone_debounce;
      $debounce_ = explode(":", $debounce);
      $now->addMinutes($debounce_[0]);
      $now->addSeconds($debounce_[1]);

      $user = $user->where([
        "phone" => $phone
      ]);
    }
    $user = $user->first();

    if(!$user){
      return response()->json([
        "success" => false,
        "message" => "User not found"
      ]);
    }

    //generate token
    $token = random_int(100000, 999999);

    if(isset($request->email)) {
      //send mail
      Mail::to($request->email)
        ->send(new SendRecoveryEmail($app_id, $request->email, $token));
    }

    if(isset($request->phone)){
      //send sms
      Nexmo::message()->send([
        'to'   => $request->phone,
        'from' => 'Yappix',
        'text' => "Recovery code: $token"
      ]);
    }

    //save token
    $userToken = AppUserToken::create([
      "app_user_id" => $user->id,
      "type" => $type,
      "token" => $token,
      "status" => "sent",
      "sent_at" => Carbon::now(),
      "expires_at" => $now
    ]);
    return response()->json([
      "success" => true,
      "message" => 'Successfully sent',
      "sent_at" => $userToken->sent_at,
      "expires_at" => $userToken->expires_at,
      "debounce" => $debounce,
    ]);
  }

  /**
   * API set new password after recovery
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function setPassword($app_id, Request $request)
  {
    //find app
    $app = App::whereId($app_id)->with('auth_settings')->first();
    if (!$app) {
      return response()->json([
        'success' => false, 'message' => "App not found"
      ]);
    }
    //find app user
    $user = AppUser::where([
      "app_id" => $app_id
    ]);
    if (isset($request->email)) {
      $user = $user->where([
        "email" => $request->email
      ]);
    }
    if (isset($request->phone)) {
      $user = $user->where([
        "phone" => $request->phone
      ]);
    }
    $user = $user->first();
    if (isset($user) && $user) {
      $token = AppUserToken::where([
        "app_user_id" => $user->id,
        "type" => "recovery",
        "token" => $request->token,
      ])->first();

      if($token){
        $user->update([
          "password" => Hash::make($request->password)
        ]);

        return response()->json([
          'success' => true,
          'message' => "Password changed successfully"
        ]);
      }

      return response()->json([
        'success' => false,
        'message' => "Recovery code not found"
      ]);
    }

    return response()->json([
      'success' => false,
      'message' => "User not found"
    ]);
  }
}
