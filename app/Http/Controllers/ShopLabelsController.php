<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopLabelList;
use Illuminate\Http\Request;

class ShopLabelsController extends YcmsController
{
  public function list(ShopLabelList $labelList)
  {
    return view('stores.labels.list', ['labels' => $this->getApp()->labels]);
  }

  public function saveLabel(Request $post)
  {
    $app = $this->getApp();

    if ($post->id) {
      $app->labels()->where('id', '=', $post->id)
        ->update(['id' => $post->id, 'title' => $post->title, 'app_id' => $post->app_id]);
    } else {
      $app->labels()->create(['title' => $post->title]);
    }

    return response()->json(['success' => true, 'responseData' => $app->labels]);
  }

  public function deleteLabel(Request $post)
  {
    $app = $this->getApp();
    $app->labels()->where('id', '=', $post->id)->delete();

    return response()->json(['success' => true, 'responseData' => $app->labels]);
  }
}
