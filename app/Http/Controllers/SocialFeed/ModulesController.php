<?php

namespace App\Http\Controllers\SocialFeed;

use App\App;
use App\SocialFeed\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModulesController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($slug)
  {
    $current_app = App::whereSlug($slug)
      ->with('feed_modules')
      ->first();

    return view('feed.modules.index', compact('current_app'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if(isset($request->module['id']) && $request->module['id']){
      Module::find($request->module['id'])->update($request->module);
      $message = 'updated';
    }else{
      Module::create($request->module);
      $message = 'created';
    }

    $modules = Module::where(['app_id' => $request->module['app_id']])->get();
    return response()->json([
      'message' => $message,
      'modules' => $modules,
    ]);
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Module  $module
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    Module::find($request->id)->delete();

    return response()->json([
      'success' => true,
    ]);
  }
}
