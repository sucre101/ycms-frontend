<?php

namespace App\Http\Controllers\SocialFeed;

use App\SocialFeed\Block;
use App\SocialFeed\Images;
use App\SocialFeed\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlocksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug, $id)
    {
      $post = Post::whereId($id)->with('blocks')->first();

      return view('feed.blocks.index', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if(isset($request->block['id']) && $request->block['id']){
        Block::find($request->block['id'])->update($request->block);
        $message = 'updated';
      }else{
        if($request->before && $before = Block::find($request->before)){
          if($before){
            $order = $before->order;

            $blocks_after = Block::
            where(['post_id' => $request->block['post_id']])->
            where('order', '>=', $order)
              ->get();
            $after_order = $order;
            foreach ($blocks_after as $ba){
              $after_order++;
              $ba->update([
                'order' => $after_order
              ]);
            }
          }
        }else{
          $max_order = Block::where(['post_id' => $request->block['post_id']])->max('order');
          $order = $max_order+1;
        }

        $new_block = Block::create($request->block);
        $new_block->update([
          'order' => $order
        ]);
        $message = 'created';
      }

      $blocks = Block::where(['post_id' => $request->block['post_id']])->orderBy('order')->get();

      return response()->json([
        'message' => $message,
        'blocks' => $blocks,
      ]);
    }
    /**
     * Store image/video to block.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $block = Block::find($request->id);
      if(!$block){
        return response()->json([
          'success' => false,
          'message' => 'No data found!',
        ]);
      }
      $rules = [];
      if($block->type === 'image'){
        $rules['image'] = 'mimes:jpg,jpeg,png,bmp,tiff,svg';
        $validator = \Validator::make($request->only('image'), $rules);
      }else if($block->type === 'html') {
        $validator = \Validator::make($request->only('html'), $rules);
        $rules['html'] = 'required';
      }else if($block->type === 'video'){
        $validator = \Validator::make($request->only('video'), $rules);
        $rules['video'] = 'required';
      }

      if ($validator->fails()) {
        return response()->json([
          'success'=> false, 'message'=> $validator->messages()
        ]);
      }
      if($block->type === 'image'){
        $image = $request->image;

        $imageName = substr($image->getClientOriginalName(), 0, strrpos($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()))
          .'-'.time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('img/uploads'), $imageName);

        $block->content = '/img/uploads/'.$imageName;

      }else if($block->type === 'html') {
        $block->content = $request->html;
      }else if($block->type === 'video'){
        $block->content = $request->video;
      }
      $block->save();

      return response()->json([
        'success' => true,
        'block' => $block,
      ]);
    }

    /**
     * Change block order.
     *
     * @param  \App\SocialFeed\Block  $block
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

      $initial_order = $block->order;
      $order = $request->order;
      if ($order < $initial_order) {
        $specs = Block::whereBetween('order', [$order, $initial_order])->where('post_id', '=', $block->post_id)
          ->get();
        foreach ($specs as $sp) {
          $sp_up = Block::find($sp->id);
          $sp_up->order = $sp->order + 1;
          $sp_up->save();
        }
      } else {
        $specs = Block::whereBetween('order', [$initial_order, $order])->where('post_id', '=', $block->post_id)
          ->get();
        foreach ($specs as $sp) {
          $sp_up = Block::find($sp->id);
          $sp_up->order = $sp->order - 1;
          $sp_up->save();
        }
      }

      $block->order = $order;
      $block->save();

      $blocks = Block::where(['post_id' => $block->post_id])->orderBy('order')->get();

      return response()->json([
        'success' => true,
        'blocks' => $blocks,
      ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SocialFeed\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $block = Block::find($request->id);

      // set orders
      $blocks = Block::where('order', ">", $block->order)->where('post_id', '=', $block->post_id)
        ->get();
      foreach ($blocks as $bl) {
        $bl->update([
          'order' => $bl->order - 1
        ]);
      }
      $block->delete();

      $new_blocks = Block::where('post_id', '=', $block->post_id)->orderBy('order')
        ->get();
      return response()->json([
        'success' => true,
        'blocks' => $new_blocks
      ]);
    }
}
