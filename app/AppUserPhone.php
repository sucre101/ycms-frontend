<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUserPhone extends Model
{
  protected $table = "app_user_phones";

  public function app_user(){
    return $this->hasOne(AppUser::class);
  }
}
