<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaymentService extends Model
{

  protected $table = 'user_payment_services';

  protected $guarded = [];

  public $timestamps = false;

}
