<?php

namespace App\Http\Controllers;

use App\Actions\LayoutSetter;
use App\App;
use App\Module;
use App\Modules\Ecommerce\ShopSetting;
use App\Page;
use Illuminate\Http\Request;

class AppsController extends YcmsController {

  public function spa()
  {
    return response()->json(['status' => 'error', 'message' => 'ERROR!!!']);
  }

  public function index()
  {
    if (count($this->user->apps)===1) {
      print_r($this->user->apps);
      return redirect()->to("/app/{$this->user->apps->first()->slug}/style");
    } else {
      return redirect()->route('appsList');
    }
  }

  public function newApp()
  {
    $modules = Module::whereIn('name', Module::IN_PROD)->get();

    return view('new_app', compact('modules'));
  }

  public function getApplication(Request $post)
  {
    $app = App::where('slug', $post->slug)->first();

    return response()->json(['success' => true, 'app' => $app], 200);
  }

  public function list()
  {
    $modules = Module::whereIn('name', Module::IN_PROD)->get();

    $data = [
      'modules' => $modules,
      'apps' => $this->authUser->apps,
    ];

    return response()->json($data);
  }

  public function show(string $slug)
  {
    dd($this->user->apps->where('slug', $slug)->first());
  }

  public function createApp(Request $request)
  {
    $appName = $request->post('appName') ?? 'New app';
    $newApp = $this->user->apps()->create([
      'user_id' => $this->user->id,
      'name' => $appName
    ]);

    ShopSetting::create(['app_id' => $newApp->id]);

    $homeModule = Module::firstWhere('name', $request->homepageModule);

    $page = Page::firstWhere(['name' => 'home', 'app_id' => $newApp->id]) ?:new Page;
    $page->app_id = $newApp->id;
    $page->name = "home";
    $page->title = "Home";
    $page->short_title = "Home";
    $page->image = "";
    $page->module_desc_id = $homeModule->id;
    $page->icon = 'home';

    if ($module = $page->module) {
      $module->delete();
    }

    $page->attachModule($request->homepageModule);

    return redirect()->route('styleElements', ['slug' => $newApp->slug]);
  }

  public function storeApp(Request $request)
  {
    $appName = $request->post('appName') ?? 'New app';
    $newApp = $this->user->apps()->create([
      'user_id' => $this->user->id,
      'name' => $appName
    ]);

    $homeModule = Module::firstWhere('name', $request->homePageModule);

    $page = Page::firstWhere(['name' => 'home', 'app_id' => $newApp->id]) ?:new Page;
    $page->app_id = $newApp->id;
    $page->name = "home";
    $page->title = "Home";
    $page->short_title = "Home";
    $page->image = "";
    $page->module_desc_id = $homeModule->id;
    $page->icon = 'home';

    if ($module = $page->module) {
      $module->delete();
    }

    $page->attachModule($request->homePageModule);

    return response()->json(['app' => $newApp]);
  }

  public function edit($slug)
  {
    $app = App::where(['slug' => $slug])->first();

    return view('apps.edit', compact('app'));
  }

  public function duplicate(Request $request)
  {
    $app = App::find($request->app);

    $newApp = $app->replicate();
    $newApp->save();

    return response()->json(['app' => $newApp]);
  }

  public function delete(Request $request)
  {

    App::find($request->app)->delete();

    return response()->json(['deleted' => true]);
  }
}
