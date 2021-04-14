<?php

namespace App\Http\Controllers\Ycms\PageBuilder;

use App\Http\Controllers\Controller;
use App\Modules\PageBuilder\Block;
use App\Modules\PageBuilder\Template;
use Illuminate\Http\Request;

class BlocksController extends Controller
{

  public function blocksList($module_id)
  {
    $blocks = Block::where('user_module_id', $module_id)
      ->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();

    return response()->json([
      'success' => true,
      'blocks' => $blocks
    ]);
  }
  /**
   * store new block
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $max_order = Block::where([
      'user_module_id' => $request->block['user_module_id'],
    ])->max('order');
    $layout = 2;
    if(strpos($request->block['layout'], '-') < 1){
      $layout = (int)$request->block['layout'];
    }
    for ($i=0; $i<$layout; $i++){
      $block = Block::create($request->block);

      $layout_ = $layout;
      if(strpos($block->layout,'-')){
        if($i === 0){
          $layout_ = $request->block['layout'];
        }else{
          $layout_ = ($request->block['layout'][2]-$request->block['layout'][0]).'-'.$request->block['layout'][2];
        }
      }

      $block->update([
        'layout' => $layout_,
        'order' => $max_order+1,
        's_order' => $i+1,
      ]);
    }

    $blocks = Block::where(['user_module_id' => $request->block['user_module_id']])
      ->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();
    return response()->json([
      'success' => true,
      'blocks' => $blocks
    ]);
  }
  /**
   * update block
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $block = Block::find($request->block['id']);
    if($block){
      $block->update($request->block);

      $blocks = Block::where(['user_module_id' => $request->block['user_module_id']])
        ->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();
      return response()->json([
        'success' => true,
        'blocks' => $blocks
      ]);
    }
    return response()->json([
      'success' => false,
      'message' => "Block  not found"
    ]);
  }

  /**
   * Change block order.
   *
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */
  public function change_block(Request $request)
  {

    $block = Block::find($request->id);
    if(!$block){
      return response()->json([
        'success' => true,
        'message' => "No data block",
      ]);
    }

    $siblings = Block::where([
      'user_module_id' => $block->user_module_id,
      'order' => $block->order,
      'is_deleted' => false
    ])->get();

    $initial_order = $block->order;
    $order = $request->order;
    if ($order < $initial_order) {
      $lower = Block::whereBetween('order', [$order, $initial_order])->where([
        'user_module_id' => $block->user_module_id,
        'is_deleted' => false
      ])
        ->get();
      foreach ($lower as $l) {
        $l = Block::find($l->id);
        $l->order = $l->order + 1;
        $l->save();
      }
    } else {
      $higher = Block::whereBetween('order', [$initial_order, $order])->where([
        'user_module_id' => $block->user_module_id,
        'is_deleted' => false
      ])
        ->get();
      foreach ($higher as $h) {
        $h = Block::find($h->id);
        $h->order = $h->order - 1;
        $h->save();
      }
    }

    foreach ($siblings as $sibling) {
      $sibling->update([
        'order' => $order
      ]);
    }

    $block->update([
      'order' =>  $order,
    ]);

    $blocks = Block::where(['user_module_id' => $block->user_module_id])
      ->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();

    return response()->json([
      'success' => true,
      'blocks' => $blocks,
    ]);
  }

  /**
   * Change block order.
   *
   * @param  \App\SocialFeed\Block  $block
   * @return \Illuminate\Http\Response
   */
  public function change_block_order(Request $request)
  {
    $block = Block::find($request->id);
    if(!$block){
      return response()->json([
        'success' => true,
        'message' => "No data block",
      ]);
    }
    $s_order = $request->add === true?$block->s_order+1:$block->s_order-1;

    $sibling = Block::where([
      'user_module_id' => $block->user_module_id,
      'order' => $block->order,
      's_order' => $s_order,
      'is_deleted' => false
    ])->first();
    $sibling->update([
      's_order' =>  $block->s_order,
    ]);

    $block->update([
      's_order' =>  $s_order,
    ]);

    $blocks = Block::where(['user_module_id' => $block->user_module_id])
      ->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();

    return response()->json([
      'success' => true,
      'blocks' => $blocks,
    ]);
  }
  /**
   * set is_deleted for block
   *
   * @return \Illuminate\Http\Response
   */
  public function remove(Request $request)
  {
    $block = Block::find($request->id);
    $user_module_id = $block->user_module_id;
    $order = $block->order;
    $block->update([
      'is_deleted' => true
    ]);

    Block::where([
      'user_module_id' => $user_module_id,
      'order' => $order,
    ])->update([
      'is_deleted' => true
    ]);

    // set orders
    $blocks = Block::where('order', ">", $block->order)
      ->where([
        'user_module_id' => $user_module_id,
        'is_deleted' => false
      ])
      ->get();
    foreach ($blocks as $bl) {
      $bl->update([
        'order' => $bl->order - 1
      ]);
    }


    $blocks = Block::where(['user_module_id' => $user_module_id])
      ->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();

    return response()->json([
      'success' => true,
      'blocks' => $blocks
    ]);
  }
  /**
   * delete block
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $block = Block::find($request->id);
    $user_module_id = $block->user_module_id;
    $order = $block->order;
    $block->delete();

    Block::where([
      'user_module_id' => $user_module_id,
      'order' => $order,
    ])->delete();

    $blocks = Block::where([
      'user_module_id' => $user_module_id
    ])->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();

    return response()->json([
      'success' => true,
      'blocks' => $blocks
    ]);
  }
  /**
   * restore block
   *
   * @return \Illuminate\Http\Response
   */
  public function restore(Request $request)
  {
    $block = Block::find($request->id);
    if(!$block){
      return response()->json([
        'success' => false,
        'blocks' => 'No data found!'
      ]);
    }
    $user_module_id = $block->user_module_id;
    $order = $block->order;

    $max_order = Block::where([
      'user_module_id' => $user_module_id,
      'is_deleted' => false
    ])->max('order');

    $block->update([
      'is_deleted' => false,
      'order' => $max_order+1
    ]);

    Block::where([
      'user_module_id' => $user_module_id,
      'order' => $order,
      'is_deleted' => true
    ])->update([
      'is_deleted' => false,
      'order' => $max_order+1
    ]);

    $blocks = Block::where([
      'user_module_id' => $user_module_id
    ])->with('template')->with('elements.template')->orderBy('order')->orderBy('s_order')->get();

    return response()->json([
      'success' => true,
      'blocks' => $blocks
    ]);
  }
}

