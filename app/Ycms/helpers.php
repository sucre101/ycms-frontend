<?php

use App\App;

function bool2str($val)
{
  return $val ? 'true' : 'false';
}

function preg_replace_file($file, $regexp, $replacement, $destinationFile = false)
{
  $oldFile = file_get_contents($file);
  $newFile = preg_replace($regexp, $replacement, $oldFile);

  if($destinationFile) { $file = $destinationFile; }

  return file_put_contents($file, $newFile);
}

function copy_dir($src, $dst, $pattern = 0, $replacement = 0)
{
  $dir = opendir($src);
  @mkdir($dst);
  while(false !== ($file = readdir($dir))) {
    if (($file != '.') && ($file != '..')) {
      if (is_dir($src . '/' . $file))
        copy_dir($src . '/' . $file, $dst . '/' . $file);
      else
        if($pattern) {
          $newFileName = preg_replace($pattern, $replacement, $file);
          copy($src . '/' . $file, $dst . '/' . $newFileName);
        } else
          copy($src . '/' . $file, $dst . '/' . $file);
    }
  }
  closedir($dir);
}

function deleteDirectory($dir)
{
  if (!file_exists($dir)) {
    return true;
  }
  if (!is_dir($dir)) {
    return unlink($dir);
  }
  foreach (scandir($dir) as $item) {
    if ($item == '.' || $item == '..') {
      continue;
    }

    if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
      return false;
    }
  }

  return rmdir($dir);
}

function endClass($class)
{
  $path = explode('\\', get_class($class));
  $tail = end($path);
  return $tail;
}

function defaultApp()
{
  return App::firstWhere('folder', 'ycms-mobile');
}

function logPath($file)
{
  return storage_path("logs/{$file}.log");
}

/**
 * Method which generate slug
 * Must be rewrite latter
 * @param string $string
 * @return String
 */
function generateSlug(string $string): String
{
  return Str::slug($string, '-');
}

/**
 * @param $dir
 * @param  array  $results
 * @return array
 */
function getPrivatMediaFolder($dir, &$results = []): array
{
  $files = scandir($dir);

  foreach ($files as $key => $value) {
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
    $info = pathinfo($path);

    if (!is_dir($path)) {

      if (isset($info['extension']) && preg_match('!\.(png|jpe?g|gif|svg)$!i', $info['basename'])) {
        $results[$key] = ['type' => 'file', 'name' => implode('/', array_slice(explode('/', $path), 7))];
      }

    } else if ($value !== "." && $value !== "..") {
      $results[$key] = ['type' => 'folder', 'name' => $info['basename']];
      getPrivatMediaFolder($path, $results[$key][]);
    }
  }

  return array_values( $results );
}


