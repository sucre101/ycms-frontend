<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopSpec;
use Illuminate\Http\Request;

class ShopSpecsController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request)
  {
    $max_order = ShopSpec::where('group_id', '=', $request->group_id)->max('order');

    $spec = new ShopSpec();
    $spec->group_id = $request->group_id;
    $spec->unit_id = $request->unit_id;
    $spec->name = $request->name;
    $spec->order = $max_order + 1;
    $spec->save();

    return response()->json(['spec' => $spec]);
  }

  /**
   * change order
   *
   * @param  Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function changePosition(Request $request)
  {
    $spec = ShopSpec::find($request->id);

    $initial_order = $spec->order;
    $order = $request->order;

    if ($request->group_id===$spec->group_id) {
      if ($order < $initial_order) {
        $specs = ShopSpec::whereBetween('order', [$order, $initial_order])->where('group_id', '=', $request->group_id)
          ->get();
        foreach ($specs as $sp) {
          $sp_up = ShopSpec::find($sp->id);
          $sp_up->order = $sp->order + 1;
          $sp_up->save();
        }
      } else {
        $specs = ShopSpec::whereBetween('order', [$initial_order, $order])->where('group_id', '=', $request->group_id)
          ->get();
        foreach ($specs as $sp) {
          $sp_up = ShopSpec::find($sp->id);
          $sp_up->order = $sp->order - 1;
          $sp_up->save();
        }
      }
    } else {
      $specs = ShopSpec::where('order', ">=", $order)->where('group_id', '=', $request->group_id)
        ->get();
      foreach ($specs as $sp) {
        $sp_up = ShopSpec::find($sp->id);
        $sp_up->order = $sp->order + 1;
        $sp_up->save();
      }

      $specs_old = ShopSpec::where('order', ">", $initial_order)->where('group_id', '=', $spec->group_id)
        ->get();
      foreach ($specs_old as $spo) {
        $sp_upo = ShopSpec::find($spo->id);
        $sp_upo->order = $spo->order - 1;
        $sp_upo->save();
      }
    }


    $spec->group_id = $request->group_id;
    $spec->order = $order;
    $spec->save();

    return response()->json(['success' => true]);
  }

  /**
   * change name
   *
   * @param  Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function saveName(Request $request)
  {
    $group = ShopSpec::find($request->id);
    $group->name = $request->name;
    $group->save();

    return response()->json(['success' => true]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\JsonResponse
   */
  public function destroy(Request $request)
  {
    $spec = ShopSpec::find($request->id);

    $specs_old = ShopSpec::where('order', ">", $spec->order)->where('group_id', '=', $spec->group_id)
      ->get();
    foreach ($specs_old as $spo) {
      $sp_upo = ShopSpec::find($spo->id);
      $sp_upo->order = $spo->order - 1;
      $sp_upo->save();
    }
    $spec->delete();

    return response()->json(['success' => true]);
  }
}
