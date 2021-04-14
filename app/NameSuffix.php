<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NameSuffix extends Model
{
  protected $table = "name_suffix";

  protected $appends = ['code', 'name'];
  protected $guarded = [];

  public function getCodeAttribute(){
    return $this->id;
  }
  public function getNameAttribute(){
    return $this->title;
  }
}
