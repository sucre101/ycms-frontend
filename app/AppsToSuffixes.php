<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppsToSuffixes extends Model
{
  protected $table = "apps_to_suffixes";
  protected $guarded = [];

  public function suffixes(){
    return $this->hasMany(NameSuffix::class, 'id', 'suffix_id');
  }

  public function app(){
    return $this->hasOne(App::class);
  }
}
