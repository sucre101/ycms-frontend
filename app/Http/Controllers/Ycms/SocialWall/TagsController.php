<?php

namespace App\Http\Controllers\SocialFeed;

use App\Http\Controllers\Controller;
use App\Modules\SocialFeed\Post;
use App\Modules\SocialFeed\PostTag;
use App\Modules\SocialFeed\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tags( $id)
    {
      $tags = Tag::where('module_id', $id)->get();

      return response()->json([
        'tags' => $tags
      ]);
    }
    /**
     * List of tags in json
     *
     * @return \Illuminate\Http\Response
     */
    public function getTags( $id)
    {
      $post = Post::find($id);
      $postTag = PostTag::where(['post_id' => $post->id ])->pluck('tag_id');

      $tags = Tag::
                where(['module_id' => $post->module_id])->
                whereNotIn('id', $postTag)->
                pluck('name');

      return response()->json([
        'tags' => $tags
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $post = Post::find($request->tag['post_id']);
      $new_tag = Tag::firstOrCreate([
        'module_id' => $post->module_id,
        'name' => $request->tag['name'],
      ]);
      PostTag::firstOrCreate([
        'post_id' => $post->id,
        'tag_id' => $new_tag->id,
      ]);
      $tags = PostTag::where(['post_id' => $request->tag['post_id']])->with("tag")->get();

      return response()->json([
        'message' => 'success',
        'tags' => $tags,
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $tag = PostTag::find($request->id);
      $post_id = $tag->post_id;
      $tag->delete();
      $tags = PostTag::where(['post_id' => $post_id])->with("tag")->get();

      return response()->json([
        'success' => true,
        'tags' => $tags
      ]);
    }
}
