<?php

namespace App\Observers;

use App\App;
use Carbon\Carbon;

class AppObserver
{

  protected $user;

  public function __construct()
  {
    if (response()->json(auth('ycms')->user())) {
      $user = response()->json(auth('ycms')->user());
      if ($user) {
        $this->user = $user->original;
      }
    }
  }

  /**
   * @param  \App\App  $app
   * @return void
   */
  public function creating(App $app)
  {
    $app->folder = 'ycms-mobile';
    $app->slug = generateSlug($app->name.'-'.$this->user->id.Carbon::now()->format('dmYs'));
  }

  public function updating(App $app)
  {
    if ($app->isDirty('name')) {
      $app->slug = generateSlug($app->name.'-'.$this->user->id.Carbon::now()->format('dmYs'));
      $app->save();
    }
  }

  /**
   * Handle the app "created" event.
   *
   * @param \App\App $app
   * @return void
   */
  public function created(App $app)
  {

  }

  /**
   * Handle the app "updated" event.
   *
   * @param \App\App $app
   * @return void
   */
  public function updated(App $app)
  {
    //
  }

  /**
   * Handle the app "deleted" event.
   *
   * @param \App\App $app
   * @return void
   */
  public function deleted(App $app)
  {
    //
  }

  /**
   * Handle the app "restored" event.
   *
   * @param \App\App $app
   * @return void
   */
  public function restored(App $app)
  {
    //
  }

  /**
   * Handle the app "force deleted" event.
   *
   * @param \App\App $app
   * @return void
   */
  public function forceDeleted(App $app)
  {
    //
  }
}
