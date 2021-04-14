<?php

namespace App\Http\Controllers\API;

use App\SocialFeed\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialFeedController extends Controller
{

  /**
   * Comment
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function comment($app_id, $post_id, Request $request)
  {

    Comment::create([
      'post_id' => $post_id,
      'parent_id' => $request->parent_id,
      'html' => $request->comment,
      'app_user_id' => $request->user_id
    ]);

    return response()->json([
      'success' => true
    ]);
  }
}
