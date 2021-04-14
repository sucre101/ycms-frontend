<?php

namespace App\Http\Controllers\Ycms;

use App\App;
use App\AppSetting;
use App\ExportBuild;
use App\Http\Controllers\YcmsController;
use App\Module;
use App\Modules\WebVIew\Models\WebviewResources;
use App\Page;
use App\UserModule;
use Illuminate\Http\Request;

class AppController extends YcmsController
{
  public function getApplication(Request $post)
  {
    return response()->json(['success' => true, 'app' => App::where('slug', $post->slug)->first()]);
  }

  public function list()
  {
    return response()->json(['apps' => $this->user->apps()->with('user', 'appSettings')->get()]);
  }

  public function moduleList()
  {
    $userModules = UserModule::with('module')->where('user_id', $this->user->id)->get();

    return response()->json([
      'success' => true,
      'modules' => Module::all(),
      'userModules' => $userModules
    ]);
  }

  public function addModule(Request $request)
  {
    $module = Module::where('id', $request->post('moduleId'))->first();

    if ($module) {
      UserModule::create(['user_id' => $this->user->id, 'module_id' => $module->id, 'active' => true]);
    }

    return response()->json(['success' => true, 'modules' => UserModule::with('module')->where('user_id', $this->user->id)->get()]);
  }

  public function deleteModule(Request $request)
  {
    $module = UserModule::where('user_id', $this->user->id)->where('id', $request->post('id'))->first();

    if ($module) {
      $module->delete();
    }

    return response()->json(['success' => true, 'modules' => UserModule::where('user_id', $this->user->id)->get()]);
  }


  public function storeApp(Request $post)
  {
    $appName = $post->post('appName') ?? 'New app';
    $newApp = $this->user->apps()->create([
      'user_id' => $this->user->id,
      'name' => $appName,
    ]);

//    $homeModule = Module::firstWhere('name', $post->homePageModule);
//
//    $page = Page::firstWhere(['name' => 'home', 'app_id' => $newApp->id]) ?:new Page;
//    $page->app_id = $newApp->id;
//    $page->name = "home";
//    $page->title = "Home";
//    $page->short_title = "Home";
//    $page->image = "";
//    $page->module_desc_id = $homeModule->id;
//    $page->icon = 'home';
//
//    if ($module = $page->module) {
//      $module->delete();
//    }
//
//    $page->attachModule($post->homePageModule);

    return response()->json(['success' => true, 'app' => $newApp]);
  }

  public function delete(Request $post)
  {
    $this->user->apps()->where('id', $post->id)->delete();

    return response()->json(['deleted' => true]);
  }

  public function updateAppIcon($slug, Request $request)
  {
    $app_id = $this->getApp()->id;


    AppSetting::where(['app_id' => $app_id, 'name' => 'background_splash'])->update(['value' => $request->background]);
    AppSetting::where(['app_id' => $app_id, 'name' => 'app_icon_src'])->update(['value' => $request->logo]);

    return response()->json(['success' => true, 'settings' => AppSetting::where('app_id', $app_id)->get()]);
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function getMediaFiles(): \Illuminate\Http\JsonResponse
  {
    $folder = getPrivatMediaFolder("./img/".$this->user->user_folder);

    return response()->json(['success' => true, 'archive' => $folder]);
  }

  public function uploadFile(Request $request)
  {
    $file = $request->file('file');

    $fileName = $request->file->hashName();

    $file->move(public_path('img' . DIRECTORY_SEPARATOR . $this->user->user_folder), $fileName);

    return $this->getMediaFiles();
  }

  public function export()
  {
    $app = $this->getApp();

    if ( !$app ) {
      return response()->json(['success' => false, 'message' => 'Error! Something was wrong']);
    }

    ExportBuild::create(['app_id' => $app['id'], 'status_id' => ExportBuild::EXPORT_STATUS_START]);

    return response()->json(['success' => true, 'builds' => ExportBuild::where('app_id', $app['id'])->get()]);
  }

  public function getBuilds()
  {
    $app = $this->getApp();

    if ( !$app ) {
      return response()->json(['success' => false, 'message' => 'Error! Something was wrong']);
    }

    return response()->json(['success' => true, 'builds' => ExportBuild::where('app_id', $app['id'])->get()]);
  }

  public function finishBuild($app_id): void
  {
    ExportBuild::where('app_id', $app_id)->update(['status_id' => ExportBuild::EXPORT_STATUS_SUCCESS]);
  }

  // TODO: 1. Finish build
  //       2. Testing EC module
  //       3. Create list grind view

}
