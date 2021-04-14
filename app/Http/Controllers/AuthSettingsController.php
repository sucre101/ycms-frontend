<?php

namespace App\Http\Controllers;

use App\App;
use App\AppsToGenders;
use App\AppsToPrefixes;
use App\AppsToSuffixes;
use App\AuthSetting;
use App\Gender;
use App\NamePrefix;
use App\NameSuffix;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthSettingsController extends YcmsController
{
    public function settings(){
      $settings = $this->getApp()->auth_settings()->first();
      if(!$settings){
        $settings = AuthSetting::create([
          "app_id" => $this->getApp()->id
        ]);
      }

      $selected_suffix_id = AppsToSuffixes::where([
        "app_id" => $this->getApp()->id
      ])->pluck('suffix_id')->toArray();
      $selected_prefix_id = AppsToPrefixes::where([
        "app_id" => $this->getApp()->id
      ])->pluck('prefix_id')->toArray();
      $selected_gender_id = AppsToGenders::where([
        "app_id" => $this->getApp()->id
      ])->pluck('gender_id')->toArray();

      $selected_suffixes = NameSuffix::whereIn('id', $selected_suffix_id)->get();
      $selected_prefixes = NamePrefix::whereIn('id', $selected_prefix_id)->get();
      $selected_genders = Gender::whereIn('id', $selected_gender_id)->get();


      $suffixes = NameSuffix::whereNotIn('id', $selected_suffix_id)->get();
      $prefixes = NamePrefix::whereNotIn('id', $selected_prefix_id)->get();
      $genders = Gender::whereNotIn('id', $selected_gender_id)->get();

      return view('apps.auth.settings', compact(
        'settings',
        'suffixes',
        'prefixes',
        'genders',
        'selected_suffixes',
        'selected_prefixes',
        'selected_genders',
      ));
    }

  /**
   * Store a newly created settings or update.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
    public function settings_save(Request $request){
      print_r($request->settings);
      AuthSetting::whereId($request->settings["id"])->update($request->settings);

      $this->update_recipe_date($request->settings["app_id"]);

      return response()->json(['success' => true]);
    }

  /**
   * Save prefixes
   *
   * @param  Request  $request
   * @return JsonResponse
   */
    public function save_prefix(Request $request){
      AppsToPrefixes::where(['app_id' => $request->app_id])->delete();

      foreach ($request->destination as $key => $destination){
        AppsToPrefixes::create([
          'app_id' => $request->app_id,
          'prefix_id' => $destination['id'],
          'order' => $key+1,
        ]);
      }

      $this->update_recipe_date($request->app_id);

      return response()->json(['success' => true]);
    }
  /**
   * Save suffixes
   *
   * @param  Request  $request
   * @return JsonResponse
   */
    public function save_suffix(Request $request){
      AppsToSuffixes::where(['app_id' => $request->app_id])->delete();

      foreach ($request->destination as $key => $destination){
        AppsToSuffixes::create([
          'app_id' => $request->app_id,
          'suffix_id' => $destination['id'],
          'order' => $key+1,
        ]);
      }

      $this->update_recipe_date($request->app_id);

      return response()->json(['success' => true]);
    }
  /**
   * Save genders
   *
   * @param  Request  $request
   * @return JsonResponse
   */
    public function save_gender(Request $request){
      AppsToGenders::where(['app_id' => $request->app_id])->delete();

      foreach ($request->destination as $key => $destination){
        AppsToGenders::create([
          'app_id' => $request->app_id,
          'gender_id' => $destination['id'],
          'order' => $key+1,
        ]);
      }

      $this->update_recipe_date($request->app_id);

      return response()->json(['success' => true]);
    }

  /**
   * update recipe date
   *
   * @param  Request  $request
   * @return JsonResponse
   */
    protected function update_recipe_date($app_id){
      App::whereId($app_id)->update([
        "recipe_updated_at" => now()
      ]);
      return response()->json(['success' => true]);
    }
}
