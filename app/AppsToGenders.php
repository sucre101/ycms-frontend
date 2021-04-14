<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppsToGenders extends Model
{
    protected $table = "apps_to_genders";
    protected $guarded = [];

    public function genders(){
      return $this->hasMany(Gender::class);
    }
    public function app(){
      return $this->hasOne(App::class);
    }
}
