<?php

namespace App\Jobs;

use App\Events\IonicStarted;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Exception;
use Illuminate\Support\Facades\Log;

class LaunchIonic implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $user, $pid, $processDBEntry;
  public $max = 7200; // 2 days
  // public $timeout = 7200;
  // public $tries = (int) 60 * 60 * 24 * 2;
  protected $app;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function handle()
  {
    $this->app = $this->user->app;

    $process = Process::fromShellCommandline('ionic serve --no-open');
    $process->setWorkingDirectory($this->app->path);
    $process->setTimeout($this->max);
    $process->setIdleTimeout($this->max);
    $env = [
      'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin',
    ];
    $process->start(null, $env);
    $this->pid = $process->getPid();

    try {
      foreach ($process as $type => $data) {
        if ($process::OUT === $type) {
          Log::channel('ionic')->info($data);
        } else { // $process::ERR === $type
          Log::channel('ionic')->error($data);
        }
        $this::analyzeOut($data);
      }
    } catch(Exception $e) {
      Log::channel('ionic')->critical($e);
      $this::closeProcess($process);
    }

    if (!$process->isSuccessful()) {
      // Log::channel('ionic')->critical(new ProcessFailedException($process));
      Log::channel('ionic')->critical('Process closed not successful');
      $this::closeProcess($process);
    } else {
      Log::channel('ionic')->info('Process closed successfully');
    }
  }

  public function analyzeOut($out)
  {
    if (preg_match('/Local: (https?:\/\/.+)$/m', $out, $res)) {
      event(new IonicStarted($this->user, $res[1]));

      $this->processDBEntry = \App\Process::updateOrCreate(
        ['user_id' => $this->user->id],
        [
          'pid' => $this->pid,
          'host' => $res[1],
          'status' => 'runnig',
          'folder' => $this->app->folder,
        ]
      );
    }
  }

  public function closeProcess($process)
  {
    if ($process) $process->stop();
    // Log::info(debug_backtrace());
    Log::channel('ionic')->info('Process closed by #closeProcess');
    $this->processDBEntry->delete();
  }

  // public function failed($exception)
  // {
  //   $m = $exception->getMessage();
  //   Log::channel('ionic')->critical('Job Failed: '. $m);
  //   $this->processDBEntry->delete();
  // }
}
