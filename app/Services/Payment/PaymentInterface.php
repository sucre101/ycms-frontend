<?php

namespace App\Services\Payment;


interface PaymentInterface
{

  public function pay($card, $data);

}
