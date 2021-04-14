<?php

namespace App\Ycms;

use Spatie\Async\Pool;

class ProcessManager
{
  protected $pool;

  public function __construct()
  {
    $this->pool = Pool::create();
  }

  public function init($process)
  {
    $this->pool->add($process);
    dump($this->pool);

  }
}