<?php

namespace App\Services\Registration\Drivers;


use App\AppUser;
use App\Exceptions\ApiException;
use Hash;
use Validator;

class EmailDriver extends BaseDriver implements DriverInterface
{

  public function start($data)
  {
    $rules = [
      'name' => 'required|string',
      'email' => 'required|email|max:255|unique:app_users',
      'password' => 'required|min:6',
    ];

    $validator = Validator::make($this->formatData($data), $rules);

    if ($validator->fails()) {
      throw new ApiException('Fields not valid');
    }

    if ( !$this->checkApp() ) {
      throw new ApiException('Application not found');
    }

    return AppUser::create([
      'app_id' => $this->app_id,
      'name' => $data->name,
      'email' => $data->email,
      'status' => "new",
      'password' => Hash::make($data->password)
    ]);

  }

  public function getStatus(): int
  {
    return $this->status;
  }

  /**
   * @param $model
   */
  public function setModel($model): void
  {
    $this->model = $model;
  }

  public function setApp(int $app_id): void
  {
    $this->app_id = $app_id;
  }

}
