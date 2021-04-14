<?php

namespace App\Http\Controllers\API;

use App\App;
use App\Http\Controllers\YcmsController;
use App\Modules\Ecommerce\ShopStoreStructure;

class ShopStoreLeafController extends YcmsController
{
  /**
   * Method which return store list by $app_id
   *
   * @param $app_id
   * @return \Illuminate\Http\JsonResponse
   */
  public function index($app_id)
  {
    $app = App::where('id', $app_id)->with('stores.currency', 'settings')->first();

    $data = [];

    if ($app->settings->store_structure) {
      $app->load('stores.structure');
      $structure = $app->storeStructure()->get()->toTree();
      $data['structure'] = $structure;
    }

    $stores = [];

    foreach ($app->stores as $index => $store) {

      $stores[$index] = [
        'id' => $store['id'],
        'name' => $store['name'],
        'phone' => $store['phone'],
        'address' => $store['address'],
        'lat' => $store['lat'],
        'lon' => $store['lon'],
        'zone_id' => $store['zone_id'],
        'tax_rate' => $store['tax_rate'],
        'tax_name' => $store['tax_name'],
        'currency' => [
          'id' => $store->currency->id,
          'name' => $store->currency->name,
          'short_name' => $store->currency->short_name
        ],
        'default' => $store['default'],
        'order' => $store['order'],
      ];

      if ($app->settings->store_structure && $store->structure->count() > 0) {
        $stores[$index]['structure'] = $store->structure[0];
      }
    }

    $data['stores'] = $stores;

    return response()->json($data, 200);
  }


  /**
   * Method which return store by id
   *
   * @param $app_id
   * @param  int  $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function show($app_id, $id)
  {
    $app = App::where('id', $app_id)->first();
    $store = $app->stores()->where('id', $id)->with('currency')->first();

    $data = [
      'id' => $store['id'],
      'name' => $store['name'],
      'phone' => $store['phone'],
      'address' => $store['address'],
      'lat' => $store['lat'],
      'lon' => $store['lon'],
      'zone_id' => $store['zone_id'],
      'currency' => $store->currency->name,
      'default' => $store['default'],
    ];

    return response()->json($data, 200);
  }

}
