<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopImages;
use App\Modules\Ecommerce\ShopProduct;
use App\Modules\Ecommerce\ShopProductToUnion;
use App\Modules\Ecommerce\ShopUnion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopProductUnionsController extends Controller
{

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $union = ShopUnion::create([
      "name" => "",
      "desc" => ""
    ]);
    $unionToProduct = ShopProductToUnion::create([
      "union_id" => $union->id,
      "product_id" => $request->id,
    ]);

    $union = ShopUnion::whereId($union->id)->with('products')->with('images')->first();
    return response()->json(['union' => $union]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function addProductToUnion($app_id,Request $request)
  {

    $unionToProduct = ShopProductToUnion::create([
      "union_id" => $request->union_id,
      "product_id" => $request->product_id,
    ]);

    $union = ShopUnion::whereId($request->union_id)->with('products')->first();

    return response()->json(['union' => $union]);
  }

  /**
   * save images of union.
   *
   * @param $slug
   * @param $id
   * @param  Request  $request
   * @return JsonResponse
   */
  public function saveImage($slug, $id, Request $request)
  {
    if($request->image){
        ShopImages::save_image($id, 'union', $request->image);
    }

    $union = ShopUnion::whereId($id)->with('images')->first();
    return response()->json(['images' => $union->images]);
  }
  /**
   * change image data(order, entity).
   *
   * @param $slug
   * @param $id
   * @param  Request  $request
   * @return JsonResponse
   */
  public function changeImage($slug, Request $request)
  {

    if(ShopImages::change_image($request->id, $request->entity, $request->entity_id, $request->order)){
      return response()->json(['success' => true]);
    }

    return response()->json(['success' => false]);
  }
  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\ShopUnion  $shopUnion
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ShopProductToUnion $shopUnion)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Request $request
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
      ShopUnion::find($request->id)->delete();

      return response()->json(['success' => true]);
  }
}
