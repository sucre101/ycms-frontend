<?php

namespace App\Http\Controllers;

use App\App;
use App\UserModule;

class YcmsController extends Controller
{
  public $user;

  public $authUser;

  public $app;

  public $anon_id;

  public $slug;

  public $module;

  public function __construct()
  {

    if (response()->json(auth('ycms')->user())) {
      $user = response()->json(auth('ycms')->user());

      if ($user) {
        $this->user = $user->original;
      }
    } else {
      redirect()->to('/login');
    }

    if (request()->headers->get('app')) {
      $this->slug = request()->headers->get('app');
    }

    if (request()->headers->get('authorization')) {
      $this->anon_id = substr(request()->headers->get('authorization'), 5);
    }

    if (request()->route('module_id')) {
      $this->module = UserModule::where('id', request()->route('module_id'))->first();
    }

  }

  /**
   * Method was return app by slug
   * @return App|null
   */
  public function getApp(): ?App
  {
    if ($this->slug) {
      return $this->user->apps()->slug($this->slug);
    } else {
      return null;
    }
  }


  public function checkAnonId()
  {
    if (!$this->anon_id) {
      return response()->json(['success' => false, 'message' => 'where anon_id ?'], 401);
    }
    return true;
  }

  /**
   * @return mixed
   */
  public function getModule()
  {
    return $this->module;
  }

  /**
   * @param  mixed  $module
   */
  public function setModule($module): void
  {
    $this->module = $module;
  }
}
