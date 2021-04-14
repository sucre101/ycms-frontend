<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NamePrefix extends Model
{
  protected $table = "name_prefix";

  protected $appends = ['code', 'name'];
  protected $guarded = [];

  public function getCodeAttribute(){
    return $this->id;
  }
  public function getNameAttribute(){
    return $this->title;
  }
}
