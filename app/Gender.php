<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
  protected $appends = ['code', 'name'];
  protected $guarded = [];

  public function getCodeAttribute(){
    return $this->id;
  }
  public function getNameAttribute(){
    return $this->title;
  }
}
