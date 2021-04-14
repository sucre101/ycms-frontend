<?php

namespace App\Services\Payment;


class PaymentDriver
{

  public $driver;
  public $cardData;

  public function __construct($driver, $cardData, $authData)
  {
    $this->driver = new $driver($authData);
    $this->cardData = $cardData;
  }

  public function pay($data)
  {
    return $this->driver->pay($this->cardData, $data);
  }

}
