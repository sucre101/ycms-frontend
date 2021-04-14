<?php

namespace App\Http\Controllers\API;

use App\App;
use App\AppUserAnon;
use App\Http\Controllers\YcmsController;
use App\Modules\Ecommerce\ShopCart;
use App\Modules\Ecommerce\ShopCartToProduct;
use App\Modules\Ecommerce\ShopCategory;
use App\Modules\Ecommerce\ShopFilter;
use App\Modules\Ecommerce\ShopProduct;
use App\Modules\Ecommerce\ShopProductToLabelListToStoreLeaf;
use App\Modules\Ecommerce\ShopProductToStore;
use App\Modules\Ecommerce\ShopSpec;
use App\Modules\Ecommerce\ShopSpecGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopCategoriesController extends YcmsController {

  public function categories($app_id, $category_id)
  {
    $category_id = $category_id ? $category_id : null;

    $categories = ShopCategory::with('image')
      ->where('app_id', $app_id)
      ->where('id', $category_id)
      ->first();

    $child = ShopCategory::with('image')
      ->where('app_id', $app_id)
      ->where('parent_id', $category_id)
      ->defaultOrder()
      ->get();

    $breadcrumbs = null;
    if ($category_id) {
      $breadcrumbs = ShopCategory::ancestorsAndSelf($category_id);
    }

    return response()->json(['category' => $categories, 'child' => $child, 'breadcrumbs' => $breadcrumbs]);
  }

  /**
   * Get category products.
   *
   * @param $category_id
   * @return JsonResponse
   */
  public function getProducts($app_id, $store_id, $category_id, Request $request)
  {
    $filters = $request->all();
    $filter_keys = array_keys($filters);
    $filter_values = array_values($filters);
    $productIds = [];
    $filteredIds = [];

    foreach ($filter_keys as $fk => $fkey){
      $filter_ = ShopFilter::find($fkey);
      if($filter_){
        //get filter's spec_data
        foreach ($filter_->spec->spec_datas as $sdata)
        {
          $json_data = $sdata->data;
          $filter_data = json_decode($filter_values[$fk]);
          if($filter_data[0] == "="){
            //if equal, check for intersect of two data
            if(array_intersect($filter_data[1],$json_data)){
              array_push($productIds, $sdata->product_id);
            }
          }else if($filter_data[0] == "<>"){
            $jd_counter = 0;
            //if range, check spec_data one by one; if in range break;
            for ($i=1;$i<count($filter_data);$i++) {
              foreach ($json_data as $jd) {
//                echo "// $jd => ".$filter_data[$i][0]." === ".$filter_data[$i][1]."\n";
                if ($filter_data[$i][0] <= $jd && $jd <= $filter_data[$i][1]) {
                  $jd_counter++;
                  break;
                }
              }
              if($jd_counter){
                break;
              }
            }
            if($jd_counter){
              array_push($productIds, $sdata->product_id);
            }
          }
        }
      }
    }
    //check filter array and product_id
    foreach (array_count_values($productIds) as $key => $id_p){
      if($id_p == count($filter_values)){
        array_push($filteredIds, $key);
      }
    }

    $products = ShopProduct::select(
      'shop_products.id',
      'shop_products.name',
      'shop_products.sku',
      'shop_products.desc',
      'shop_products.created_at',
      'shop_products.updated_at',
      'shop_products.desc',
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
      }])
      ->with('union.products');


    //get  filtered product ids
    if(count($filter_keys) > 0){
      $products = $products->whereIn('shop_products.id', $filteredIds);
    }

    $products = $products->where('category_id', $category_id)->get();
    $spec_ids = [];

    $groups = ShopSpecGroup::with('specs')
      ->with('specs.unit')
      ->with('specs.filter')
      ->where('category_id', $category_id)
      ->orderBy('shop_spec_groups.order')
      ->get();

    foreach ($products as $product) {
      $product->label_lists = $product->labelToStores;
      unset($product->labelToStores);

      $product->price = (double)$product->price;
      $product->old_price = (double)$product->old_price;

      foreach ($groups as $gkey => $group) {
        $count_specs = 0;
        foreach ($group->specs as $key => $spec) {

          $data = $spec->spec_data($product->id);
          if ($data && $data->data) {
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

      //unions
      if ($product->union) {
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

    $specs = [];
    foreach ($groups as $gkey => $group) {
      foreach ($group->specs as $key => $spec) {
        if ($spec->filter) {
          array_push($specs, $spec);
        }
        if($spec->filter && !count($spec->filter->groupings)){
          unset($spec->filter->groupings);
          if($spec->spec_data_values()){
            $spec->filter->offsetSet('values', $spec->spec_data_values());
          }
        }
      }
    }
    return response()->json([
      'products' => $products,
      'filters' => $specs,
    ]);
  }
}
