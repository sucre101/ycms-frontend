<?php

use App\Events\IonicStarted;
use App\Process;
use App\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/



Artisan::command('inspire', function () {
  $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('rsup', function () {
  exec('while killall -s 15 supervisord; do sleep 1; done; supervisord -c ./supervisord.conf');
  shell_exec("pkill 'ng run'");
  Process::truncate();
  shell_exec('php artisan clearlogs');
})->describe('Restarts supervisor');


Artisan::command('clearlogs', function(){
  $logs = collect(explode("\n", shell_exec('ls storage/logs')))
    ->filter(function($name){ return strpos($name, '.log'); });

  $logs->each(function($log){
    $path = 'storage/logs/' . $log;
    if (strpos($log, 'laravel-') === 0) {
      unlink($path);
      // $this->info("Deleted ${path}");
      return;
    };
    file_put_contents($path, '');
  });
})->describe('Clears log files, deletes laravel-date files');

Artisan::command('logionic {str}', function($str){
  Log::channel('ionic')->info($str);
})->describe('Logs string to ionic log');


Artisan::command('build-app {app_id}', function($id) {
  echo $id;
})->describe('Build application');


Artisan::command('launch-ionic {userId}', function($userId) {
  // TODO: change to user curent app
  $app = App\App::firstWhere('user_id', $userId);
  $this->info($app);

  $process = $process = proc_open(
    'ionic serve --no-open',
    [['pipe', 'r'], ['pipe', 'w'], ['file', logPath('ionic'), 'a']],
    $pipes,
    $app->path
  );
  // $env = [
  //   'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin',
  // ];
  $pid = proc_get_status($process)['pid'];

  $analyzeOut = function($out) use ($userId, $pid, $app)
  {
    if (preg_match('/Local: (https?:\/\/.+)$/m', $out, $res)) {
      event(new IonicStarted($app->user, $res[1]));

      $processDBEntry = \App\Process::updateOrCreate(
        ['user_id' => $userId],
        [
          'pid' => $pid,
          'host' => $res[1],
          'status' => 'runnig',
          'folder' => $app->folder,
        ]
      );
    }

    Log::channel('ionic')->info($out);
  };

  if (is_resource($process)) {
    while ($chunk = fgets($pipes[1], 1024)) {
      $analyzeOut($chunk);
    }
  }
})->describe('Launches current app of user');
