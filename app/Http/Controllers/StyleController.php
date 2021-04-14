<?php

namespace App\Http\Controllers;

use App\Actions\TopBarImagesSaver;
use App\Http\Requests\UpdateTopBar;

class StyleController extends YcmsController
{
  /**
   * Method was rewrite and rename, because he return view with once app
   * @param string $slug
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
   * @throws \Exception
   */
  public function element(string $slug)
  {
    $app = $this->user->apps()->slug($slug);

    return view('style.elements', compact('app'));
  }

  public function updateTopBar(UpdateTopBar $request, $slug)
  {
    $app = $this->user->apps()->slug($slug);

    $app->storeMeta(['top-bar-bg-type' => $request['top-bar-bg-type']]);

    $bgStyle = [
      'selector' => 'ion-toolbar',
      'propName' => "--background",
      'propVal' => "",
      'group' => 'top-bar',
      'ruleName' => 'top-bar-bg',
    ];

    if ($request['top-bar-bg-type'] == 'image') {
      $croppedName = (new TopBarImagesSaver($app, $request))->execute();
      $bgStyle['propVal'] = "center/cover url(assets/{$croppedName})";
      $app->storeStyle($bgStyle);
    }

    if ($request['top-bar-bg-type'] == 'gradient') {
      $bgStyle['propVal'] = $request['top-bar-gradient'];
      $app->storeStyle($bgStyle);
    }

    if ($request['top-bar-bg-type'] == 'solid-color') {
      $bgStyle['propVal'] = $request['top-bar-bg-color'];
      $app->storeStyle($bgStyle);
    }

    $app->storeStyle([
      'selector' => 'ion-toolbar ion-title',
      'propName' => "--color",
      'propVal' => $request['top-bar-text-color'],
      'group' => 'top-bar',
      'ruleName' => 'top-bar-text-color',
    ]);

    $app->storeStyle([
      'selector' => 'ion-toolbar ion-menu-button',
      'propName' => "--color",
      'propVal' => $request['top-bar-icon-color'],
      'group' => 'top-bar',
      'ruleName' => 'top-bar-icon-color',
    ]);

    $app->generateStyleFor('top-bar');

    return ['async' => 'compiling'];
  }
}
