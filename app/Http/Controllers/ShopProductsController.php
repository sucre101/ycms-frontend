<?php

namespace App\Http\Controllers;

use App\App;
use App\Modules\Ecommerce\Product;
use App\Modules\Ecommerce\ShopCategory;
use App\Modules\Ecommerce\ShopImages;
use App\Modules\Ecommerce\ShopLabelList;
use App\Modules\Ecommerce\ShopProduct;
use App\Modules\Ecommerce\ShopProductSpec;
use App\Modules\Ecommerce\ShopProductToLabelListToStoreLeaf;
use App\Modules\Ecommerce\ShopProductToStore;
use App\Modules\Ecommerce\ShopSpec;
use App\Modules\Ecommerce\ShopSpecGroup;
use App\Modules\Ecommerce\ShopProductToUnion;
use App\Modules\Ecommerce\ShopUnion;
use App\Modules\Ecommerce\ShopUnit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShopProductsController extends YcmsController
{
  /**
   * Display a listing of the resource.
   *
   * @param $slug
   * @param  Request  $post
   * @return View|JsonResponse
   */
  public function index($slug)
  {
    $app = App::whereSlug($slug)->with('stores')->first();

    return view('stores.products.list', compact('app'));
  }

  public function getProducts($slug, Request $post)
  {
    $take = $post->take;
    $skip = $post->skip;

    $products = ShopProduct::with('thumb', 'category')
      ->take($take)
      ->skip($skip)
      ->orderBy('id', 'ASC')
      ->get();

    return response()->json(['success' => true, 'message' => 'loaded', 'products' => $products]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @param $slug
   * @param $category_id
   * @return Application|Factory|View
   */
  public function create($slug, $category_id)
  {
    $units = ShopUnit::get();
    $categories = $this->getApp()->categories()->with('spec_groups')->with('spec_groups.children')->get();
    $app = App::whereSlug($slug)->with('stores')->first();
    $category = ShopCategory::where(['id' => $category_id])->with('spec_groups')->with('spec_groups.children')->first();
    $labels = ShopLabelList::all();

    foreach ($categories as $cat){
      foreach ($cat->spec_groups as $group){
        foreach ($group->children as $spec){
          $spec->offsetSet('data', []);
        }
      }
    }
    $product = new ShopProduct();
    $product->category_id = $category_id;
    return view('stores.products.create', compact('app', 'category', 'categories', 'units', 'product', 'labels'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function store(Request $request)
  {
    $newProduct = new ShopProduct();
    if ($request->id) {
      $newProduct = $newProduct->findOrFail($request->id);
    }
    $newProduct->name = $request->name;
    $newProduct->category_id = $request->category_id;
    $newProduct->sku = $request->sku;
    $newProduct->desc = "";
    $newProduct->save();

    $spec_groups = ShopSpecGroup::where(['category_id' => $newProduct->category_id])->with('specs')->get();
    $labels = ShopLabelList::all();

    return response()->json(['id' => $newProduct->id, 'spec_groups' => $spec_groups, 'labels' => $labels]);
  }

  /**
   * save images of product.
   *
   * @param $slug
   * @param $id
   * @param  Request  $request
   * @return JsonResponse
   */
  public function saveImage($slug, $id, Request $request)
  {
    if(gettype($request->image) == "object"){
      ShopImages::save_image($id, 'product', $request->image);
    }
    if(gettype($request->image) == "array"){
      foreach ($request->image as $image) {
        ShopImages::save_image($id, 'product', $image);
      }
    }

    $product = ShopProduct::whereId($id)->with('images')->first();
    return response()->json(['id' => $product->id, 'images' => $product->images ]);
  }

  /**
   * delete image of product.
   *
   * @param  Request  $request
   * @return void
   */
  public function deleteImage( Request $request)
  {
    if($request->id){
      ShopImages::delete_image($request->id);
    }

  }

  /**
   * Display the specified resource.
   *
   * @param  ShopProduct  $shopProduct
   * @return void
   */
  public function show(ShopProduct $shopProduct)
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
    $product = ShopProduct::whereId($id)
      ->with('category')
      ->with('category.spec_groups')
      ->with('category.spec_groups.children')
      ->with('images')
      ->with('toProduct')
      ->with('labels')
      ->with('labelToStores')
      ->with('union')
      ->with('union.products')
      ->with('union.images')
      ->first();

    $units = ShopUnit::all();
    $categories = $this->getApp()->categories()->with('spec_groups')->with('spec_groups.children')->get();

    foreach ($categories as $category){
      if($category->id == $product->category_id){
        foreach ($category->spec_groups as $group){
          foreach ($group->children as $spec){
            $spec_data = $spec->spec_data($id);
            $spec->offsetSet('data',$spec_data?$spec_data->data:[]);
          }
        }
      }
    }
    $labels = ShopLabelList::all();

    return view('stores.products.edit', compact('categories', 'units', 'product', 'labels'));
  }

  /**
   * get product in Json.
   *
   * @param $slug
   * @param $id
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function getProductJson(Request $request)
  {
    $product = ShopProduct::whereId($request->id)
      ->with('category')
      ->with('category.spec_groups')
      ->with('category.spec_groups.specs')
      ->with('images')
      ->with('toProduct')
      ->with('labels')
      ->with('labelToStores')
      ->with('union')
      ->with('union.products')
      ->with('union.images')
      ->first();

    $categories = $this->getApp()->categories()->with('spec_groups')->with('spec_groups.children')->get();

    foreach ($categories as $category){
      if($category->id == $product->category_id){
        foreach ($category->spec_groups as $group){
          foreach ($group->children as $spec){
            $spec_data = $spec->spec_data($request->id);
            $spec->offsetSet('data',$spec_data?$spec_data->data:[]);
          }
        }
      }
    }

    return response()->json([
      "product" => $product,
      "categories" => $categories,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  Request  $request
   * @param ShopProductToStore $productToStore
   * @return JsonResponse
   */
  public function update(Request $request, ShopProductToStore $productToStore)
  {

    if(isset($request->product['id'])){
      $product = ShopProduct::firstOrCreate(['id' => $request->product['id']]);
    }else{
      $product = new ShopProduct();
    }
    if(!$request->product['name']){
      return response()->json([
        'error' => true,
        'message' => "You missed product name"
      ]);
    }
    $product->name = $request->product['name'];
    $product->category_id = $request->product['category_id'];
    $product->sku = isset($request->product['sku']) ? $request->product['sku'] : "";
    $product->desc = isset($request->product['desc']) ? $request->product['desc'] : "";
    $product->save();

    //union data
    if (isset($request->product['union'])) {
      ShopUnion::find($request->product['union']['id'])->update([
        'desc' => $request->product['union']['desc']? $request->product['union']['desc']: "",
        'name' => $request->product['union']['name']? $request->product['union']['name']: ""
      ]);
    }
    if (isset($request->product['prices'])) {
      $prices = $request->product['prices'];

      // update prices and etc columns
      foreach ($prices as $price) {

        $price['pivot']['quantity'] = !is_null($price['pivot']['quantity']) ? $price['pivot']['quantity'] : 0;

        $productToStore->updateOrCreate([
          'app_id' => $this->getApp()->id,
          'store_id' => $price['pivot']['store_id'],
          'product_id' => $product->id
        ], $price['pivot']);
      }

    }

    //clear product_spec_data
    ShopProductSpec::where(['product_id' => $product->id])->delete();
    //save product_spec_data

    if ($request->groups) {
      foreach ($request->groups as $group) {
        foreach ($group['children'] as $key => $spec) {
          ShopSpec::find($spec['id'])
            ->update(['unit_id' => $spec['unit_id']]);

          $specData = new ShopProductSpec();
          $specData->product_id = $product->id;
          $specData->spec_id = $spec['id'];
          $specData->data = json_encode($spec['data']);
          $specData->order = $key + 1;
          $specData->save();
        }
      }
    }

    // save or delete labels to store to product
    foreach ($request->labels as $label) {
      foreach ($label['stores'] as $store) {
        if ($store['checked']) {
          ShopProductToLabelListToStoreLeaf::firstOrCreate([
            'product_id' => $product->id,
            'label_list_id' => $label['id'],
            'store_id' => $store['id']
          ]);
        } else {
          ShopProductToLabelListToStoreLeaf::where([
            'product_id' => $product->id,
            'label_list_id' => $label['id'],
            'store_id' => $store['id']
          ])->delete();
        }
      }
    }

    return response()->json(['product' => $product]);
  }

  /**
   * get product & union images
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getImages(Request $request)
  {
    $product = ShopProduct::whereId($request->id)->with('images')->with('union.images')->first();

    return response()->json([
      "product_images" => $product->images,
      "union_images" => $product->union->images,
    ]);
  }
  /**
   * get products to add union
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function getProductsToUnion(Request $request)
  {
    $productsToUnion = ShopProductToUnion::where(['union_id' => $request->union_id])->pluck('product_id');

    $products = ShopProduct::where("category_id", "=", $request->category_id)
      ->whereNotIn("id", $productsToUnion)
      ->get();

    return response()->json($products);
  }
  /**
   * Remove from union
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function removeUnion(Request $request)
  {
    ShopProductToUnion::where([
      "product_id" => $request->id,
    ])->delete();

    return response()->json(['deleted' => true]);
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function destroy(Request $request)
  {
    ShopProduct::find($request->id)->delete();

    return response()->json(['deleted' => true]);
  }
}
