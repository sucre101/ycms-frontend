<?php

namespace App\Actions;

class TopBarImagesSaver
{
  private $app, $request;

  public function __construct($app, $request)
  {
    $this->app = $app;
    $this->request = $request;
  }

  public function execute()
  {
    @unlink(storage_path("app/{$this->app->getMeta('top-bar-original-img-path')}"));
    @unlink($this->app->getMeta('top-bar-cropped-img-path'));

    $originalPath =
      $this->request->file('top-bar-bg-image')->store('original-images');
    $origFileName = $this->request->file('top-bar-bg-image')
      ->getClientOriginalName();

    $meta = [
      'top-bar-original-file-name' => $origFileName,
      'top-bar-original-img-path' => $originalPath,
    ];

    $cropped = $this->request->file('cropped-top-bar-bg-image');
    $fileName = 'top-bar-bg-image.'
      . $cropped->getClientOriginalExtension();
    $croppedPath = $cropped->move($this->app->path . '/src/assets', $fileName);
    $meta['top-bar-cropped-img-path'] = $croppedPath;

    $this->app->storeMeta($meta);

    return $fileName;
  }
}