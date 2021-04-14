<?php

namespace App\Http\Controllers\API;

use App\AppImages;
use App\AppsToGenders;
use App\AppsToPrefixes;
use App\AppsToSuffixes;
use App\AppUser;
use App\AuthSetting;
use App\Gender;
use App\NamePrefix;
use App\NameSuffix;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ProfilesController extends Controller
{

  /**
   * API App user get data
   *
   * @param $app_id
   * @param $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function profile($app_id, $id)
  {

    $images = [
      [ 'src' => 'https://wall2mob.com/m/wp-DishonoredVideoGameWide_37616-cprw.jpg?i=37616&w=640&h=480&fdl=0' ],
      [ 'src' => 'https://wall2mob.com/m/wp-DishonoredVideoGameWide_37616-cprw.jpg?i=37616&w=640&h=480&fdl=0' ],
      [ 'src' => 'https://wall2mob.com/m/wp-DishonoredVideoGameWide_37616-cprw.jpg?i=37616&w=640&h=480&fdl=0' ],
      [ 'src' => 'https://wall2mob.com/m/wp-DishonoredVideoGameWide_37616-cprw.jpg?i=37616&w=640&h=480&fdl=0' ],
      [ 'src' => 'https://wall2mob.com/m/wp-DishonoredVideoGameWide_37616-cprw.jpg?i=37616&w=640&h=480&fdl=0' ],
    ];

    return response()->json(['images' => $images]);die();

    $app_user = AppUser::where($id)
      ->where('app_id', $app_id)
      ->with('phones')
      ->with('addresses')
      ->with('avatar')
      ->with('profile')
      ->first();

    if (!$app_user) {
      return response()->json(['success' => false, 'message' => '123'], 404);
    }

    return response()->json($app_user, 200);

//    $prefix_id = AppsToPrefixes::where(["app_id" => $app_id])->pluck('prefix_id')->toArray();
//    $prefixes = NamePrefix::whereIn('id', $prefix_id)->get();
//
//    $suffix_id = AppsToSuffixes::where(["app_id" => $app_id])->pluck('suffix_id')->toArray();
//    $suffixes = NameSuffix::whereIn('id', $suffix_id)->get();
//
//    $gender_id = AppsToGenders::where(["app_id" => $app_id])->pluck('gender_id')->toArray();
//    $genders = Gender::whereIn('id', $gender_id)->get();


  }
  /**
   * API App user save
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update($app_id, Request $request)
  {
    $app_user = AppUser::find($request->id);

    if ( !$app_user ) {
      return response()->json([
        "success" => false,
        "message" => "No user found"
      ]);
    }

    $settings = AuthSetting::where(['app_id' => $app_id])->first()->toArray();
    $rules = $this->check_required($settings);

    $validator = Validator::make($request->only(array_keys($rules)), $rules);

    if ( $validator->fails() ) {
      return response()->json([
        'success'=> false, 'message'=> $validator->messages()
      ]);
    }

    $app_user->update([
      "name" => $request->name ? $request->name: $app_user->name,
      "prefix_id" => $request->prefix_id ? $request->prefix_id: $app_user->prefix_id,
      "suffix_id" => $request->suffix_id ? $request->suffix_id: $app_user->suffix_id,
      "gender_id" => $request->gender_id ? $request->gender_id: $app_user->gender_id,
      "first_name" => $request->first_name ? $request->first_name: $app_user->first_name,
      "last_name" => $request->last_name ? $request->last_name: $app_user->last_name,
      "middle_name" => $request->middle_name ? $request->middle_name: $app_user->middle_name,
      "birthday" => $request->birthday ? $request->birthday: $app_user->birthday
    ]);

    if ( isset($request->avatar) ) {

      $avatar = AppImages::where([
        'entity' => 'app_user_avatar',
        'entity_id' => $app_user->id
      ])->first();

      if ( $avatar ) {
        AppImages::delete_image($avatar->id);
      }

      if ( $request->file('avatar') ) {
        AppImages::save_image($app_user->id, 'app_user_avatar', $request->file('avatar'));
      }
    }

    return response()->json([
      "success" => true,
      "app_user" => $app_user
    ]);
  }

  /**
   * API App user save avatar
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function avatar($app_id, Request $request)
  {
    $app_user = AppUser::find($request->id);

    if ( !$app_user ) {
      return response()->json([
        "success" => false,
        "message" => "No user found"
      ]);
    }

    $avatar = AppImages::where(['entity' => 'app_user_avatar', 'entity_id' => $app_user->id])->first();

    if ( $avatar) {
      AppImages::delete_image($avatar->id);
    }

    if ( $request->avatar ) {
      AppImages::save_image($app_user->id, 'app_user_avatar', $request->avatar);
    }

    return response()->json([
      "success" => true,
      "app_user" => $app_user
    ]);
  }

  protected function check_required($settings)
  {
    $arr = [];

    foreach ( array_values($settings) as $key => $row ) {
      if ( $row === 'required' ) {
        $arr[array_keys($settings)[$key]] = 'required';
      }
    }
    return $arr;
  }
}
