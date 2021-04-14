<?php

namespace App\Services\Registration\Drivers;


interface DriverInterface
{

  /**
   * @param $data
   * @return mixed
   */
  public function start($data);

  public function getStatus();

  public function setModel(string $model);

  public function setApp(int $app_id);

}
