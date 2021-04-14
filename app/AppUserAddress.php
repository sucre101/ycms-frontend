<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUserAddress extends Model
{
  protected $table = "app_user_addresses";

  public function app_user(){
    return $this->hasOne(AppUser::class);
  }
}
