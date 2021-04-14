<?php

namespace App\Ycms;

use Spatie\Async\Task;

use App\User;
use Symfony\Component\Process\Process;
use Exception;
use Illuminate\Support\Facades\Log;

class IonicProcess extends Task
{
  protected /*$user,*/ $process, $env;

  // public function __construct(User $user)
  // {
  //   $this->user = $user;
  // }

  public function configure()
  {
    // $app = $this->user->app;
    dump(auth());
    $app = auth()->user()->app;

    $this->process = Process::fromShellCommandline('ionic serve --no-open');
    $this->process->setWorkingDirectory($app->path);
    $this->process->setTimeout(PHP_INT_MAX);
    $this->process->setIdleTimeout(1800);
    $this->env = [
      'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin',
    ];
    dump("configr");
  }

  public function run()
  {
    // dump($this->user);
    $app = auth()->user()->app;

    $this->process = Process::fromShellCommandline('ionic serve --no-open');
    $this->process->setWorkingDirectory($app->path);
    $this->process->setTimeout(PHP_INT_MAX);
    $this->process->setIdleTimeout(1800);
    $this->env = [
      'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin',
    ];
    $this->process->start(null, $this->env);

    try {
      foreach ($this->process as $type => $data) {
        if ($this->process::OUT === $type) {
          Log::channel('ionic')->info($data);
          dump('i: '.$data);
        } else { // $process::ERR === $type
          Log::channel('ionic')->warning($data);
          dump('w: '.$data);
        }
      }
    } catch(Exception $e) {
      Log::channel('ionic')->error($data);
      dump('e: '.$data);
    }
  }
}