<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUserNotification extends Model
{

  public const LEVEL_CORE = 1;
  public const LEVEL_MODULE = 2;
  public const LEVEL_APPLICATION = 3;

  public const STATUS_SENT = 1001;
  public const STATUS_READED = 1002;

  protected $guarded = [];

}
