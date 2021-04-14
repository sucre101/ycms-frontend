<?php

namespace App\Services\Registration;


use App\Exceptions\ApiException;

class RegistrationService
{

  protected $driver;
  protected $info;

  public const EMAIL_DRIVER = 1;
  public const PHONE_DRIVER = 2;

  public $drivers_list = [
    1 => 'App\Services\Registration\Drivers\EmailDriver',
  ];

  public function __construct($data)
  {
    $this->checkDriver($data->post('type'));
    $this->info = $data;
  }

  public function init($app_id)
  {
    $model = (new $this->driver);
    $model->setApp($app_id);
    try {
      $model->start($this->info);
    } catch (ApiException $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
  }

  public function setDriver($driver)
  {
    return $this->driver = $driver;
  }

  public function getDriver()
  {
    return $this->driver;
  }

  /**
   * @return mixed
   */
  public function getInfo()
  {
    return $this->info;
  }

  /**
   * @param  mixed  $info
   */
  public function setInfo($info): void
  {
    $this->info = $info;
  }

  public function checkDriver($data)
  {

    $driver = $this->drivers_list[self::EMAIL_DRIVER];

    if ( $data !== 'email' ) {
      $driver = $this->drivers_list[self::PHONE_DRIVER];
    }

    return $this->setDriver($driver);
  }

}
