<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExportBuild extends Model
{
  protected $guarded = [];

  public const EXPORT_STATUS_START = 1;
  public const EXPORT_STATUS_SUCCESS = 2;

  public function app()
  {
    return $this->hasOne(App::class, 'id', 'app_id');
  }
}
