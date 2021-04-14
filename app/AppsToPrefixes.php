<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppsToPrefixes extends Model
{
  protected $table = "apps_to_prefixes";
  protected $guarded = [];

  public function prefixes(){
    return $this->hasMany(NamePrefix::class, 'id', 'prefix_id');
  }
  public function app(){
    return $this->hasOne(App::class);
  }
}
