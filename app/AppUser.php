<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\AppImages;

class AppUser extends Authenticatable  implements JWTSubject
{
    protected $table = "app_users";
    protected $guarded = [];

  // Rest omitted for brevity

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

  public function app(){
    return $this->hasOne(App::class, 'id', 'app_id');
  }

  public function phones(){
    return $this->hasMany(AppUserPhone::class, 'app_user_id', 'id');
  }

  public function addresses(){
    return $this->hasMany(AppUserAddress::class, 'app_user_id', 'id');
  }

  public function avatar()
  {
    return $this->hasOne(AppImages::class, 'entity_id', 'id')
      ->where('entity', '=','app_user_avatar')->orderBy('order','desc');
  }

  public function profile()
  {
    return $this->hasOne(AppUserProfile::class, 'app_user_id', 'id');
  }
}
