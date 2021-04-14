<?php


namespace App\Services\Notification;

use App\AppUserNotification;

class NotificationService
{

  public $app_id;
  public $user_id;
  public $level_id;
  public $module_id;
  public $message;

  public function __construct($app_id, $user_id, $level_id, $message, $module_id = null)
  {
    $this->app_id = $app_id;
    $this->level_id = $level_id;
    $this->user_id = $user_id;
    $this->module_id = $module_id;
    $this->message = $message;
  }

  public function send(): AppUserNotification
  {
    return AppUserNotification::create([
      'app_id' => $this->app_id,
      'app_user_anon_id' => $this->user_id,
      'module_id' => $this->module_id,
      'level_id' => $this->level_id,
      'status_id' => AppUserNotification::STATUS_SENT,
      'data' => \GuzzleHttp\json_encode($this->message)
    ]);
  }

}
