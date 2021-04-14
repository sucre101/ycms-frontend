<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use Notifiable, HasApiTokens;

  protected $fillable = [
    'name', 'lastname', 'email', 'password',
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function app()
  {
    return $this->hasOne(App::class);
  }

  /**
   * Relation for table Apps
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function apps()
  {
    return $this->hasMany(App::class, 'user_id', 'id');
  }

  public function modules()
  {
    return $this->hasMany(UserModule::class, 'module_id', 'id');
  }

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
}
