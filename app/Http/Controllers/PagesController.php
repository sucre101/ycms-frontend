<?php

namespace App\Http\Controllers;

use App\Actions\LayoutSetter;
use App\Module;
use App\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagesController extends YcmsController {

  public $module;

  public function __construct(Module $module)
  {
    $this->module = $module;
    parent::__construct();
  }

  public function index()
  {
    $app = $this->getApp();

    $pages = $app->pages()->withTrashed()->whereNull('parent_id')
      ->with('module', 'modDesc')->orderBy('order')->get();
    $modules = $this->module->whereIn('name', Module::IN_PROD)->get();

    return view('pages.index', compact('app', 'pages', 'modules'));
  }

  public function newPage(Request $request)
  {
    $app = $this->getApp();

    $pageModule = Module::firstWhere('name', $request->pageModule);
    $page = new Page;
    $page->app_id = $app->id;
    $nextId = Page::withTrashed()->max('id') + 1;
    $page->name = "page{$nextId}";
    $page->image = "";
    $page->module_desc_id = $pageModule->id;
    $page->icon = $pageModule->icon;
    $page->order = Page::max('order') + 1;

    $page->attachModule($pageModule->name);

    $page = $page->fresh()->load('module', 'modDesc');

    if ($request->parent) {
      $page->parent_id = $request->parent ? $request->parent['id'] : null;
      $page->save();
      $page->parent->updateInIonic();
    }
    $page->exportToIonic();
    $app->exportRoutes();
    $app->exportPagesConfig();

    return ['async' => 'compiling', 'page' => $page];
  }

  public function updatePages(Request $request, $slug)
  {
    $app = $this->getApp();

    if ($request->parent) {
      $page = $app->pages()->find($request->parent['id']);
      $page->icon = $request->parent['icon'];
      $page->title = $request->parent['title'];
      $page->active = $request->parent['active'];
      $page->save();
      $page->updateInIonic();
    }

    $pagesToUpdate = $request->pages;
    if ($request->parent) {
      array_push($pagesToUpdate, $request->parent);
    }
    foreach($pagesToUpdate as $page)
    {
      $app->pages()->find($page['id'])->update([
        'active' => $page['active'],
        'tabbar' => $page['tabbar'],
        'order' => $page['order'],
        'icon' => $page['icon'],
      ]);
    }

    $deleteIds = collect($request->toDelete)->map(function($page){
      return $page['id'];
    });
    $app->pages()->whereIn('id', $deleteIds)->update([
      'deleted_at' => Carbon::now(),
      'order' => null,
    ]);

    foreach($request->toRestore as $pageToRestore)
    {
      $page = $app->pages()->withTrashed()->find($pageToRestore['id']);
      $page->order = $pageToRestore['order'];
      $page->icon = $pageToRestore['icon'];
      $page->restore();
    }

    foreach($request->deletePermanently as $page)
    {
      $page = $app->pages()->withTrashed()->find($page['id']);
      $page->module()->delete();
      $page->forceDelete();
      $page->removeFromIonic();
    }

    $app->exportRoutes();
    $app->exportPagesConfig();

    return ['async' => 'compiling'];
  }

  public function home()
  {
    $app = $this->getApp();
    $modules = $this->module->whereIn('name', Module::IN_PROD)->get();

    return view('pages.home', compact('app', 'modules'));
  }

  public function setHome(Request $request)
  {
    $this->getApp()->storeSettings(['menuTemplate' => $request->menuTemplate], true);

    $homeModule = $this->module->firstWhere('name', $request->homepageModule);

    $page = Page::firstWhere(['name' => 'home', 'app_id' => $this->getApp()->id]) ?: new Page;
    $page->app_id = $this->getApp()->id;
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

    (new LayoutSetter)->invoke($this->getApp(), $request->menuTemplate);

    return ['message' => 'success'];
  }

  public function editPage($slug, $pageId)
  {
    $app = $this->getApp();

    $page = $app->pages()
      ->withDepth()
      ->where('id', $pageId)
      ->with('children.module', 'children.modDesc', 'module')->first();
    $modules = $this->module->whereIn('name', Module::IN_PROD)->get();

    return view('pages.edit', compact('app', 'page', 'modules'));
  }
}
