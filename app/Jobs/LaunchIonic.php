<?php

namespace App\Jobs;

use App\Events\IonicStarted;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LaunchIonic implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $user, $pid, $processDBEntry;
  // public $max = 7200; // 2 days
  // public $timeout = 7200;
  // public $tries = (int) 60 * 60 * 24 * 2;
  protected $app;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function handle()
  {
    shell_exec("nohup php artisan launch-ionic {$this->user->id}");
  }
}
