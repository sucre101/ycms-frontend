<?php

namespace App\Http\Middleware;

use App\Jobs\LaunchIonic;
use Closure;

class CheckAppStatus
{
  public function handle($request, Closure $next)
  {
    $user = $request->user();
    // If user has no app redirects to page to create one
    $pathIsNotAllowed = !in_array($request->path(), ['new-app', 'create-app']);
    if (!$user->app && $pathIsNotAllowed) {
      return redirect('/new-app');
    }
    // If user has app and it not launched - launches it
    if ($user->app && !$this->procIsRunning($user)) {
      LaunchIonic::dispatch($user);
    }

    return $next($request);
  }

  private function procIsRunning($user)
  {
    $process = $user->process;
    if (!$process) {
      return false;
    } else {
      return !!shell_exec("lsof -p {$process->pid}");
    }
  }
}
