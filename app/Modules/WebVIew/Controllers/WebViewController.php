<?php

namespace App\Modules\WebVIew\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\WebVIew\Models\WebviewResources;
use Illuminate\Http\Request;

class WebViewController extends Controller
{

  public function getWebViewId($moduleId)
  {
    $module = WebviewResources::firstOrCreate([
      'user_module_id' => $moduleId
    ]);

    return response()->json(['success' => true, $module]);
  }

  public function saveData($moduleId, Request $request)
  {
    WebviewResources::where('user_module_id', $moduleId)->update(['src' => $request->post('src')]);

    return response()->json(['success' => true]);
  }



}
