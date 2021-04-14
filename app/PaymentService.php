<?php

namespace App;

use App\Services\Payment\StripeDriver;

class PaymentService
{

  public static $services = [

    1 => StripeDriver::class,
    2 => 'PayPal'

  ];

  public static function getPaymentServices()
  {
    return self::$services;
  }

}
