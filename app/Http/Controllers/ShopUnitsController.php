<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopUnit;
use Illuminate\Http\Request;

class ShopUnitsController extends YcmsController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $units = ShopUnit::get();
    return view('stores.units.index', compact('units'));
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    ShopUnit::create([
      "name" => $request->name,
      "type" => $request->type
    ]);

    $units = ShopUnit::get();
    return response()->json(['units' => $units]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    ShopUnit::find($request->id)->update([
      "name" => $request->name,
      "type" => $request->type
    ]);

    $units = ShopUnit::get();
    return response()->json(['units' => $units]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    ShopUnit::find($request->id)->delete();

    $units = ShopUnit::get();
    return response()->json(['units' => $units]);
  }
}
