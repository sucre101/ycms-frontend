<?php

namespace App\Providers;

use App\App;
use App\Observers\AppObserver;
use App\Ycms\ProcessManager;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Object_;
use App\Observers\UserObserver;
use App\User;


class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
//    view()->composer('*', function($view) {
//      $view->with('user', auth()->user());
//
//      $parse_url = explode('/', request()->route('any'));
//
//      if (isset($parse_url[2])) {
//        $view->with('app', auth()->user()->apps()->slug($parse_url[1]));
//      } else {
//        $view->with('app', collect(['app_id' => false]));
//      }
//    });

    // $this->app->singleton(ProcessManager::class, function() {
    //     return new ProcessManager;
    // });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Queue::failing(function (JobFailed $event) {
      // $event->connectionName
      // $event->job
      // $event->exception
      Log::channel('ionic')->critical($event);
    });
    App::observe(AppObserver::class);
    User::observe(UserObserver::class);
  }
}
