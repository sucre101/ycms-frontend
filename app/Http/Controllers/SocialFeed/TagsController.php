<?php

namespace App\Http\Controllers\SocialFeed;

use App\Http\Controllers\Controller;
use App\SocialFeed\Post;
use App\SocialFeed\PostTag;
use App\SocialFeed\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug, $id)
    {
      $post = Post::whereId($id)->with('post_tags.tag')->first();

      return view('feed.tags.index', compact('post'));
    }
    /**
     * List of tags in json
     *
     * @return \Illuminate\Http\Response
     */
    public function getTags($slug, $id)
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

    public function update(Request $request)
    {
        //
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
