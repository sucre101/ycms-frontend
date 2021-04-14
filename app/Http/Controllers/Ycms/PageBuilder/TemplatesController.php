<?php

namespace App\Http\Controllers\Ycms\PageBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\PageBuilder\Template;

class TemplatesController extends Controller
{
  /**
   * get templates list
   *
   * @return \Illuminate\Http\Response
   */
  public function templatesList($module_id)
  {
    $templates = Template::where('user_module_id', $module_id)->orWhereNull('user_module_id')->orderBy('id')->get();

    return response()->json([
      'success' => true,
      'templates' => $templates
    ]);
  }
  /**
   * store new template
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    Template::create($request->style);

    $templates = Template::where(['user_module_id' => $request->style['user_module_id']])->orderBy('id')->get();
    return response()->json([
      'success' => true,
      'templates' => $templates
    ]);
  }
  /**
   * update template
   *
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $template = Template::find($request->style['id']);
    if($template){
      $template->update($request->style);

      $templates = Template::where(['user_module_id' => $request->style['user_module_id']])->orderBy('id')->get();
      return response()->json([
        'success' => true,
        'templates' => $templates
      ]);
    }
    return response()->json([
      'success' => false,
      'message' => "Template  not found"
    ]);
  }
  /**
   * delete template
   *
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $template = Template::find($request->id);
    $user_module_id = $template->user_module_id;
    $template->delete();

    $templates = Template::where(['user_module_id' => $user_module_id])->orderBy('id')->xget();

    return response()->json([
      'success' => true,
      'templates' => $templates
    ]);
  }
}
