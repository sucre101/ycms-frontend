<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\Category;
use App\Modules\Ecommerce\ShopCategory;
use App\Modules\Ecommerce\ShopFilter;
use App\Modules\Ecommerce\ShopFilterExtra;
use App\Modules\Ecommerce\ShopFilterGrouping;
use App\Modules\Ecommerce\ShopImages;
use App\Modules\Ecommerce\ShopProduct;
use App\Modules\Ecommerce\ShopProductSpec;
use App\Modules\Ecommerce\ShopProductToStore;
use App\Modules\Ecommerce\ShopSpec;
use App\Modules\Ecommerce\ShopSpecGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use function foo\func;

class ShopCategoriesController extends YcmsController
{
  public $category;

  public function __construct(ShopCategory $shopCategory)
  {
    $this->category = $shopCategory;
    parent::__construct();
  }

  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View
   */
  public function index()
  {
    $app = $this->getApp();
    $categories = $this->getApp()->categories()->defaultOrder()->get()->toTree();

    return view('stores.categories.index', compact('categories', 'app'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return void
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request)
  {
    $category = new ShopCategory();
    $category->app_id = $request->app_id;
    $category->name = $request->name;
    $category->parent_id = $request->parent_id;
    $category->save();

    return response()->json(['category' => $category]);
  }

  /**
   * Change name of category
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function changeName(Request $request)
  {
    $category = ShopCategory::find($request->id);
    $category->name = $request->name;
    $category->save();

    return response()->json(['status' => true]);
  }

  /**
   * Change image of category
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function changeImage(Request $request)
  {
    $image = ShopImages::where([
      'entity_id' => $request->id,
      'entity' => 'category'
    ])->first();
    if($image){
      ShopImages::delete_image($image->id);
    }
    ShopImages::save_image($request->id, 'category', $request->image);

    return response()->json(['status' => true]);
  }

  /**
   * Display the specified resource.
   *
   * @param ShopCategory $shopCategory
   * @return void
   */
  public function show(ShopCategory $shopCategory)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param $slug
   * @param $id
   * @return Application|Factory|View
   */
  public function edit($slug, $id)
  {
    $shopCategory = ShopCategory::where('id', $id)
      ->with('image')
      ->with('products')
      ->with('products.union')
      ->first();
    $app = $this->getApp();
    return view('stores.categories.edit', compact('shopCategory', 'app'));
  }

  /**
   * duplicate category.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function duplicate(Request $request)
  {
    $category = ShopCategory::find($request->id);

    $newCategory = $category->replicate();
    $newCategory->name = "copy $category->name";
    $newCategory->parent_id = $category->parent_id;
    $newCategory->save();

    $image = ShopImages::where(['entity_id' => $request->id, 'entity' => "category"])
      ->orderBy('id', 'desc')
      ->first();

    if ($image) {
      $newImage = $image->replicate();
      $newImage->entity_id = $newCategory->id;
      $newImage->save();
    }
    $categories = $this->getApp()->categories()->get()->toTree();

    $specGroups = ShopSpecGroup::where(['category_id' => $category->id])
      ->with('children')
      ->with('children.filter')
      ->with('children.filter.extras')
      ->with('children.filter.groupings')
      ->get();
    foreach ($specGroups as $group) {
      $newGroup = $group->replicate();
      $newGroup->category_id = $newCategory->id;
      $newGroup->save();

      foreach ($group->children as $spec) {
        $newSpec = $spec->replicate();
        $newSpec->group_id = $newGroup->id;
        $newSpec->save();

        if ($spec->filter) {
          $filter = $spec->filter;
          $newFilter = $filter->replicate();
          $newFilter->spec_id = $newSpec->id;
          $newFilter->save();

          foreach ($filter->extras as $extra) {
            $newExtra = $extra->replicate();
            $newExtra->filter_id = $newFilter->id;
            $newExtra->save();
          }
          foreach ($filter->groupings as $grouping) {
            $newExtra = $grouping->replicate();
            $newExtra->filter_id = $newFilter->id;
            $newExtra->save();
          }

        }
      }

    }

    return response()->json(['categories' => $categories]);
  }

  /**
   * Rebuild category tree.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function rebuild(Request $request)
  {
    $category = ShopCategory::find($request->id);
    $category->parent_id = $request->parent_id;
    $category->save();

    ShopCategory::rebuildTree($request->target);

    return response()->json(['rebuilt' => true]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function destroy(Request $request)
  {
    ShopCategory::find($request->id)->delete();
    $categories = $this->getApp()->categories()->defaultOrder()->get()->toTree();

    return response()->json(['categories' => $categories]);
  }

}
