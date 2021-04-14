<?php

namespace App\Http\Controllers\Ycms;

use App\Http\Controllers\YcmsController;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PageController extends YcmsController
{
  public function index()
  {
    $app = $this->getApp();
    $pages = Page::with('userModule.module')->where('app_id', $app->id)->get();

    return response()->json(['success' => true, 'pages' => $pages]);
  }

  public function create(Request $request)
  {
    $app = $this->getApp();

    if (!$app) {
      return false;
    }

    if (!$request->post('id')) {

      Page::create([
        'app_id' => $app->id,
        'user_module_id' => $request->post('user_module_id'),
        'name' => $request->post('name'),
        'title' => $request->post('title'),
        'short_title' => $request->post('short_title'),
        'active' => $request->post('active'),
        'order' => 100
      ]);

    } else {
      $this->getApp()->pages()
        ->where('id', $request->post('id'))
        ->update(Arr::except($request->post(), ['id']));
    }

    $pages = Page::with('userModule.module')->where('app_id', $app->id)->get();

    return response()->json(['success' => true, 'pages' => $pages]);
  }

  public function updateOrders(Request $request)
  {

    $limit = count($request->post());

    for ($i = 0; $i < $limit; $i++) {
      $id = $request->post()[$i];
      Page::where('id', $id)->update(['order' => $i]);
    }
  }

  public function delete(Request $request)
  {
    Page::where('id', $request->post('id'))->delete();

    return response()->json(['success' => true, 'pages' => $this->getApp()->pages]);
  }
}
