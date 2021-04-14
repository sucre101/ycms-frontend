<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\ShopLabelList;
use App\Modules\Ecommerce\ShopProductToLabelListToStoreLeaf;
use App\Modules\Ecommerce\ShopStoreLeaf;
use Illuminate\Http\Request;

class ShopLabelListsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param $app_id
   * @param $store_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function labelsByStoreId($app_id, $store_id)
  {
    $store = ShopStoreLeaf::where('id', $store_id)->with('toProduct')->with('labels.label')->with('labels.products.images')->first();

    $data = [];

    foreach ($store->labels->groupBy('label_list_id') as $index => $item) {
      $data[$index] = [];
      $data[$index]['id'] = $item[0]['label']['id'];
      $data[$index]['title'] = $item[0]['label']['title'];
      $data[$index]['products'] = [];

      foreach ($item as $product) {
        array_push($data[$index]['products'], $product->products[0]->toArray());
      }
    }

    return response()->json($data, 200);
  }

  public function labelsByStoreIdAndLabelListId($slug, $store_id, $label_list_id)
  {
    if ((int)$label_list_id === 0) {
      return $this->labelsByStoreId($slug, $store_id);
    }

    $labels = ShopProductToLabelListToStoreLeaf::where(['store_id' => $store_id, 'label_list_id' => $label_list_id])
      ->with('products.images')
      ->with('products.toProduct')
      ->with('label')
      ->get()
      ->toArray();

    $data = [];

    foreach ($labels as $label) {
      $data['id'] = $label['label_list_id'];
      $data['title'] = $label['label']['title'];
      $data['products'][] = $label['products'][0];
    }

    $new = [];

    foreach ($data['products'] as $index => $product) {
      $new[$index]['id'] = $product['id'];
      $new[$index]['category_id'] = $product['category_id'];
      $new[$index]['name'] = $product['name'];
      $new[$index]['desc'] = $product['desc'];
      $new[$index]['sku'] = $product['sku'];
      $new[$index]['images'] = $product['images'];

      foreach ($product['to_product'] as $item) {
        if ($item['pivot']['store_id'] == $store_id) {
          $new[$index]['price'] = $item['pivot']['price'];
          $new[$index]['old_price'] = $item['pivot']['old_price'];
          $new[$index]['discount'] = $item['pivot']['discount'];
          $new[$index]['quantity'] = $item['pivot']['quantity'];
        }
      }
    }

    $data['products'] = $new;

    return response()->json($data, 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show()
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
