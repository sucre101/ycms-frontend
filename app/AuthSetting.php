<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthSetting extends Model
{
  protected $table = "app_auth_settings";

  protected $attributes = [
    'email' => true,
    'email_debounce' => "00:15:00",
    'phone' => true,
    'phone_debounce' => "00:01:30",
    'facebook' => false,
    'facebook_app_id' => "",
    'google_plus' => false,
    'google_plus_app_id' => "",
    'google_plus_android_client_id' => "",
    'vkontakte' => false,
    'vkontakte_app_id' => "",
    'twitter' => false,
    'twitter_app_id' => "",
    'name' => 'required',
    'first_name' => false,
    'last_name' => false,
    'middle_name' => false,
    'prefix_id' => true,
    'suffix_id' => true,
    'gender_id' => true,
  ];
  protected $guarded = [];

  public function app(){
    return $this->hasOne(App::class, 'id', 'app_id');
  }
}
