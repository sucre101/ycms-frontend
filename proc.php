<?php

namespace App;

use Exception;
use Symfony\Component\Process\Process;

$app = currentApp();

$process = new Process(['ionic','serve', '--no-open']);
$process->setWorkingDirectory($app->path);
$process->setTimeout(PHP_INT_MAX);
$process->setIdleTimeout(1800);
$process->start();

try {
  foreach ($process as $type => $data) {
    if ($process::OUT === $type) {
      echo "\n".$data;
    } else { // $process::ERR === $type
      echo "\nIonError: ".$data;
    }
  }
} catch(Exception $e) {
  echo 'Error : ' . $e->getMessage();
}