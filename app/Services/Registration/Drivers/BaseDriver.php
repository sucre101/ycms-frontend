<?php

namespace App\Services\Registration\Drivers;


use App\App;

class BaseDriver
{

  protected $status;

  protected $model = 'App\AppUser';

  protected $app_id;

  public function checkApp(): bool
  {
    $app = App::where('id', $this->app_id)->first();

    if ( is_null($app) ) {
      return false;
    }
    return true;
  }

  public function formatData($data)
  {
    return [
      'name' => $data->post('name'),
      'password' => $data->post('password'),
      $data->post('type') => $data->post('value')
    ];
  }

}
