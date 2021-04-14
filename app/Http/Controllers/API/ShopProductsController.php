<?php

namespace App\Http\Controllers\API;

use App\App;
use App\AppUserAnon;
use App\Http\Controllers\YcmsController;
use App\Modules\Ecommerce\ShopCart;
use App\Modules\Ecommerce\ShopCartToProduct;
use App\Modules\Ecommerce\ShopProduct;
use App\Modules\Ecommerce\ShopProductToLabelListToStoreLeaf;
use App\Modules\Ecommerce\ShopProductToStore;
use App\Modules\Ecommerce\ShopSpecGroup;
use Illuminate\Http\Request;

class ShopProductsController extends YcmsController {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param $app_id
   * @param $store_id
   * @param $product_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($app_id, $store_id, $product_id) {

    $product = ShopProduct::select(
      'shop_products.id',
      'shop_products.name',
      'shop_products.category_id',
      'shop_products.sku',
      'shop_products.desc',
      'shop_products.created_at',
      'shop_products.updated_at',
      'shop_products_to_stores.price',
      'shop_products_to_stores.old_price',
      'shop_products_to_stores.discount',
      'shop_products_to_stores.quantity',
      )
      ->join('shop_products_to_stores', function($join) use($store_id)
      {
        $join->on('shop_products.id', '=', 'shop_products_to_stores.product_id');
        $join->where('shop_products_to_stores.store_id', '=', $store_id);
        $join->where('shop_products_to_stores.quantity', '>', 0);
      })
      ->with(['category.spec_groups.specs.spec_datas' => function($query) use($product_id){
        $query->where("product_id", "=", $product_id);
      }])->with('category.spec_groups.specs.unit')
      ->with('union.products')
      ->with('images')
      ->with(['labelToStores' => function($query) use($store_id){
        $query->where("shop_product_to_label_list_to_store_leaf.store_id", "=", $store_id)->with('label');
      }])->where(["shop_products.id" => $product_id])
      ->first();

    if(!$product){
      return response()->json($product);
    }

    $product->price = (double)$product->price;
    $product->old_price = (double)$product->old_price;

    $product->label_lists = $product->labelToStores;
    unset($product->labelToStores);

    $product->spec_groups = $product->category->spec_groups;
    unset($product->category);


    //unions
    if($product->union){
      foreach ($product->union->products as $prod) {

        $to_store = $prod->toProductByStore($store_id)->first();
        $prod->price = (double)$to_store->price;
        $prod->old_price = (double)$to_store->old_price;
        $prod->discount = $to_store->discount;
        $prod->quantity = $to_store->quantity;

        $groups = ShopSpecGroup::with('specs')
          ->with('specs.unit')
          ->where('category_id', $prod->category_id)
          ->orderBy('shop_spec_groups.order')
          ->get();
        foreach ($groups as $gkey => $group) {
          $count_specs = 0;
          foreach ($group->specs as $key => $spec) {

            $data = $spec->spec_data($prod->id);
            if ($data && $data->data && !empty($data->data)) {
              $spec->offsetSet('spec_datas', $data);
              $count_specs++;
            } else {
              unset($group->specs[$key]);
            }
          }
          if ($count_specs == 0) {
            unset($groups[$gkey]);
          }
        }
        $prod->offsetSet('spec_groups', $groups);

        $prod->label_lists = $prod->labelToStoresByStore($store_id)->get();
      }
    }


    return response()->json($product, 200);
  }
  /**
   * Display the specified resource.
   *
   * @param $app_id
   * @param $store_id
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function chosen_products($app_id, $store_id, Request $request) {

    $ids = json_decode($request->product_ids);

    $products = ShopProduct::select(
      'shop_products.id',
      'shop_products.name',
      'shop_products.category_id',
      'shop_products.sku',
      'shop_products.desc',
      'shop_products.created_at',
      'shop_products.updated_at',
      'shop_products_to_stores.price',
      'shop_products_to_stores.old_price',
      'shop_products_to_stores.discount',
      'shop_products_to_stores.quantity',
      )
      ->join('shop_products_to_stores', function($join) use($store_id)
      {
        $join->on('shop_products.id', '=', 'shop_products_to_stores.product_id');
        $join->where('shop_products_to_stores.store_id', '=', $store_id);
        $join->where('shop_products_to_stores.quantity', '>', 0);
      })
      ->with('images')
      ->with(['labelToStores' => function($query) use($store_id){
        $query->where("shop_product_to_label_list_to_store_leaf.store_id", "=", $store_id)->with('label');
      }])->whereIn("shop_products.id", $ids)
      ->with('union.products')
      ->get();

    if(!count($products)){
      return response()->json($products);
    }
    foreach ($products as $product) {
      $product->price = (double)$product->price;
      $product->old_price = (double)$product->old_price;
      $groups = ShopSpecGroup::with('specs')
        ->with('specs.unit')
        ->where('category_id', $product->category_id)
        ->orderBy('shop_spec_groups.order')
        ->get();
      foreach ($groups as $gkey => $group) {
        $count_specs = 0;
        foreach ($group->specs as $key => $spec) {

          $data = $spec->spec_data($product->id);
          if ($data && $data->data && !empty($data->data)) {
            $spec->offsetSet('spec_datas', $data);
            $count_specs++;
          } else {
            unset($group->specs[$key]);
          }
        }
        if ($count_specs == 0) {
          unset($groups[$gkey]);
        }
      }
      $product->offsetSet('spec_groups', $groups);

      $product->label_lists = $product->labelToStores;
      unset($product->labelToStores);

      //unions
      if($product->union) {
        foreach ($product->union->products as $prod) {

          $to_store = $prod->toProductByStore($store_id)->first();
          $prod->price = (double)$to_store->price;
          $prod->old_price = (double)$to_store->old_price;
          $prod->discount = $to_store->discount;
          $prod->quantity = $to_store->quantity;

          $groups = ShopSpecGroup::with('specs')
            ->with('specs.unit')
            ->where('category_id', $prod->category_id)
            ->orderBy('shop_spec_groups.order')
            ->get();
          foreach ($groups as $gkey => $group) {
            $count_specs = 0;
            foreach ($group->specs as $key => $spec) {

              $data = $spec->spec_data($prod->id);
              if ($data && $data->data && !empty($data->data)) {
                $spec->offsetSet('spec_datas', $data);
                $count_specs++;
              } else {
                unset($group->specs[$key]);
              }
            }
            if ($count_specs == 0) {
              unset($groups[$gkey]);
            }
          }
          $prod->offsetSet('spec_groups', $groups);

          $prod->label_lists = $prod->labelToStoresByStore($store_id)->get();
        }
      }
    }
    return response()->json($products, 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    //
  }

  public function productToCart($app_id, $store_id, Request $post)
  {
    $product = ShopProduct::find($post->product_id)->first();

    if (!$product) {
      return response()->json(['success' => false, 'message' => 'invalid post data']);
    }

    $productPrice = ShopProductToStore::where(['app_id' => $app_id, 'store_id' => $store_id, 'product_id' => $product->id])->first();

    if (!$productPrice) {
      return response()->json(['success' => false, 'message' => 'invalid post data']);
    }

    if ($post->quantity > $productPrice->quantity) {
      return response()->json(['success' => false, 'message' => 'The quantity of goods ordered is greater than the quantity in stock']);
    }

    if (!$post->anon_id) {
      return response()->json(['success' => false, 'message' => 'empty anon_id']);
    }

    $user_anon = AppUserAnon::where('uuid', $post->anon_id)->first();

    if (!$user_anon) {
      $user_anon = new AppUserAnon(['app_id' => $app_id, 'uuid' => $post->anon_id]);
      $user_anon->save();
    }

    $cart = ShopCart::where('anon_id', $user_anon->id)->first();

    if (!$cart) {
      $cart = new ShopCart(['anon_id' => $user_anon->id]);
      $cart->save();
    }

    ShopCartToProduct::create([
      'cart_id' => $cart->id,
      'product_id' => $product->id,
      'store_id' => $store_id,
      'quantity' => $post->quantity,
      'price' => $productPrice->price
    ]);

    return response()->json(['success' => true, 'message' => 'Product will be added to your cart']);
  }
}
