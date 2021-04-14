<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopSpecGroup;
use Illuminate\Http\Request;

class ShopSpecGroupsController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    $max_order = ShopSpecGroup::where('category_id','=',$request->category_id)->max('order');

    $newGroup = new ShopSpecGroup();
    $newGroup->category_id = $request->category_id;
    $newGroup->name = $request->name;
    $newGroup->order = $max_order+1;
    $newGroup->save();

    $group = ShopSpecGroup::where('id','=',$newGroup->id)->with('specs')->first();


    return response()->json(['group' => $group]);
  }

  /**
   * change order
   *
   * @param  Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function changePosition(Request $request)
  {
    $group = ShopSpecGroup::find($request->id);

    $initial_order = $group->order;
    $order = $request->order;

    if($order < $initial_order){
      $groups = ShopSpecGroup::whereBetween('order',[$order, $initial_order])->where('category_id', '=', $group->category_id)
        ->get();
      foreach ($groups as $gr){
        $gr_up = ShopSpecGroup::find($gr->id);
        $gr_up->order = $gr->order+1;
        $gr_up->save();
      }
    }else{
      $groups = ShopSpecGroup::whereBetween('order',[$initial_order,$order])->where('category_id', '=', $group->category_id)
        ->get();
      foreach ($groups as $gr){
        $gr_up = ShopSpecGroup::find($gr->id);
        $gr_up->order = $gr->order-1;
        $gr_up->save();
      }
    }

    $group->order = $order;
    $group->save();

    return response()->json(['success' => true ]);
  }

  /**
   * change name
   *
   * @param  Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function saveName(Request $request)
  {
    $group = ShopSpecGroup::find($request->id);
    $group->name = $request->name;
    $group->save();

    return response()->json(['success' => true ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Request $request)
  {
    $spec = ShopSpecGroup::find($request->id);

    $specs_old = ShopSpecGroup::where('order', ">", $spec->order)->where('category_id', '=', $spec->category_id)
      ->get();
    foreach ($specs_old as $spo){
      $sp_upo = ShopSpecGroup::find($spo->id);
      $sp_upo->order = $spo->order-1;
      $sp_upo->save();
    }
    $spec->delete();

    return response()->json(['success' => true ]);
  }
}
