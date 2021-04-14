<?php

namespace App\Http\Controllers;

use App\App;
use Illuminate\Http\Request;

class SettingsController extends YcmsController
{
  public function index()
  {
    $app = $this->getApp();;

    return view('config.index', compact('app'));
  }

  public function fixPages($slug)
  {
    $app = $this->user->apps()->slug($slug);
    $appPages = $app->pages()->pluck('name')->filter(function($name){
      return \Str::startsWith($name, 'page');
    });
    $ionicPages = collect(scandir($app->path('src/pages')))
    ->reduce(function($carry, $file) use ($app) {
      return !\Str::startsWith($file, '.') && \Str::startsWith($file, 'page')
        ? $carry->push($file) : $carry;
    }, collect());

    $toDelete = $appPages->filter(function($page) use ($ionicPages) {
      return !$ionicPages->contains($page);
    });
    // $toDelete->each(function($page) use ($app) {
    //   deleteDirectory($app->path("src/pages/{$page}"));
    // });
    $app->pages()->whereIn('name', $toDelete)->forceDelete();

    $app->exportPagesConfig();
    $app->exportRoutes();

    return ['success' => "Deleted {$toDelete->count()} pages"];
  }

  public function setOSMode(Request $request, $slug)
  {
    $request->validate([
      'mode' => 'required|in:default,md,ios'
    ]);

    $app = $this->user->apps()->slug($slug);
    $app->storeSettings(['osmode' => $request->mode]);
    $app->generateConfig();

    return ['async' => 'compiling'];
  }

  public function updateSettings($slug, Request $post)
  {
    $this->getApp()->settings()->update(['store_structure' => $post->store_structure]);

    return response()->json(['success' => true]);
  }
}
