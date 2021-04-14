<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
  protected $fillable = ['name', 'value'];

  public function app()
  {
    return $this->belongsTo(App::class);
  }
}
