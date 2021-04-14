<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

  protected $guards = [];

  /**
   * Get the path the user should be redirected to when they are not authenticated.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return string
   */
  protected function redirectTo($request)
  {
    if (!$request->expectsJson()) {
      return route('login');
    }
  }

  public function handle($request, Closure $next, ...$guards)
  {

    if (auth()->guard('ycms')->check() || auth()->guard('api')->check()) {
      return parent::handle($request, $next, ...$guards);
    }
    return response()->json(['message' => 'Unauthorized'], 401);

  }
}
