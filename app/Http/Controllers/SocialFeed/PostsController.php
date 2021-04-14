<?php

namespace App\Http\Controllers\SocialFeed;

use App\SocialFeed\Module;
use App\SocialFeed\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($slug, $id)
  {
    $module = Module::whereId($id)->with('posts')->first();

    return view('feed.posts.index', compact('module'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if(isset($request->post['id']) && $request->post['id']){
      Post::find($request->post['id'])->update($request->post);
      $message = 'updated';
    }else{
      Post::create($request->post);
      $message = 'created';
    }

    $posts = Post::where(['module_id' => $request->post['module_id']])->get();
    return response()->json([
      'message' => $message,
      'posts' => $posts,
    ]);
  }

  /**
   * Publish post.
   *
   * @param  Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function publish(Request $request)
  {
    $post = Post::find($request->id);

    if(!$post){
      return response()->json([
        'success' => true,
        'message' => "No data found!"
      ]);
    }

    $post->update([
      "published_at" => now()
    ]);

    return response()->json([
      'success' => true,
    ]);
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Post  $post
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    Post::find($request->id)->delete();

    return response()->json([
      'success' => true,
    ]);
  }
}
