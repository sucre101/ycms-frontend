<?php

namespace App\Services\Payment;
use Mockery\Exception;
use Stripe\StripeClient;

class StripeDriver implements PaymentInterface
{
  protected $secret;
  protected $stripe;

  public function __construct($data)
  {
    $key = null;
    foreach ($data as $item) {
      if ($item['title'] === 'secret key') {
        $key = $item['value'];
      }
    }

    $this->stripe = new StripeClient($key);
  }

  /**
   * @return mixed
   */
  public function getSecret()
  {
    return $this->secret;
  }

  /**
   * @param  mixed  $secret
   */
  public function setSecret($secret): void
  {
    $this->secret = $secret;
  }

  public function changeClient()
  {
    $this->stripe = new StripeClient($this->secret);
  }

  public function getPaymentList()
  {
    $data = $this->stripe->charges->all();
    return $data['data'];
  }

  public function generateToken($cardData)
  {
    return $this->stripe->tokens->create($cardData);
  }

  public function pay($card, $data)
  {
    $token = $this->generateToken($card);

    $data['source'] = $token;

    try {

      return $this->stripe->charges->create($data);

    } catch (Exception $e) {

      return $e->getMessage();

    }
  }
}
