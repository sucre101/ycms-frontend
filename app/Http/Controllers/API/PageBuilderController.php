<?php

namespace App\Http\Controllers\API;

use App\App;
use App\Http\Controllers\Controller;
use App\Modules\PageBuilder\Block;
use App\Modules\PageBuilder\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{
  /**
   * Get app pages.
   *
   * @param $app_id
   * @return JsonResponse
   */
  public function getPages($app_id){
    $pages = Page::where(['app_id' => $app_id])->with('active_blocks.template')->with('active_blocks.elements.template')->get();

    return response()->json([
      'pages' => $pages
    ]);
  }

  /**
   * Get page blocks.
   *
   * @param $app_id
   * @param $page_id
   * @return JsonResponse
   */
  public function getBlocks($app_id, $page_id){
    $page = Page::whereId($page_id)->with('active_blocks.template')->with('active_blocks.elements.template')->first();

    return response()->json([
      'page' => $page
    ]);
  }

  /**
   * Get block elements.
   *
   * @param $app_id
   * @param $page_id
   * @return JsonResponse
   */
  public function getElements($app_id, $block_id){
    $block = Block::whereId($block_id)->with('elements.template')->first();

    return response()->json([
      'block' => $block
    ]);
  }
}
