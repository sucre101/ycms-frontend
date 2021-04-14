<?php

namespace App\Http\Controllers;

use App\Modules\Ecommerce\ShopFilter;
use App\Modules\Ecommerce\ShopFilterExtra;
use App\Modules\Ecommerce\ShopFilterGrouping;
use App\Modules\Ecommerce\ShopSpec;
use App\Modules\Ecommerce\ShopSpecGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopCategoryFiltersController extends YcmsController
{
  /**
   * filters list.
   *
   * @param $slug
   * @param $id
   * @return Application|Factory|View
   */
  public function filters($slug, $id)
  {
    $groups = ShopSpecGroup::where(['category_id' => $id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();
    $app = $this->getApp();
    return view('stores.categories.filter', compact('groups', 'app', 'id'));
  }

  /**
   * save filter.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function filter_save(Request $request)
  {

    $filter = new ShopFilter();
    if ($request->id){
      $filter = ShopFilter::find($request->id);
    }
    $filter->spec_id = $request->spec_id;
    $filter->display_type = $request->display_type;
    $filter->selectable = $request->selectable;
    $filter->save();

    $spec = ShopSpec::find($request->spec_id);
    $groups = ShopSpecGroup::where(['category_id' => $spec->group->category_id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();

    return response()->json(['groups' => $groups]);
  }

  /**
   * save filter extras.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function filter_extra_save(Request $request)
  {
    $extra = new ShopFilterExtra();

    if ($request->id){
      $extra = ShopFilterExtra::find($request->id);
    }
    $extra->filter_id = $request->filter_id;
    $extra->type = $request->type;
    $extra->show = $request->show;
    $extra->n = $request->n;
    $extra->save();

    $filter = ShopFilter::find($request->filter_id);
    $groups = ShopSpecGroup::where(['category_id' => $filter->spec->group->category_id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();
    return response()->json(['groups' => $groups]);
  }

  /**
   * save filter groupings.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function filter_grouping_save(Request $request)
  {
    $group = new ShopFilterGrouping();
    if ($request->id){
      $group = ShopFilterGrouping::find($request->id);
    }

    $group->filter_id = $request->filter_id;
    $group->name = $request->name;
    $group->more_than = $request->more_than;
    $group->less_than = $request->less_than;
    $group->save();

    $filter = ShopFilter::find($request->filter_id);
    $groups = ShopSpecGroup::where(['category_id' => $filter->spec->group->category_id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();

    return response()->json(['groups' => $groups]);
  }

  /**
   * delete filter.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function filter_delete(Request $request)
  {
    $filter = ShopFilter::find($request->id);
    $category_id = $filter->spec->group->category_id;
    ShopFilter::find($request->id)->delete();

    $groups = ShopSpecGroup::where(['category_id' => $category_id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();

    return response()->json(['groups' => $groups]);
  }

  /**
   * delete filter extras.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function filter_extra_delete(Request $request)
  {
    $extra = ShopFilterExtra::find($request->id);
    $category_id = $extra->filter->spec->group->category_id;

    ShopFilterExtra::find($request->id)->delete();

    $groups = ShopSpecGroup::where(['category_id' => $category_id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();

    return response()->json(['groups' => $groups]);
  }

  /**
   * delete filter groupings.
   *
   * @param  Request  $request
   * @return JsonResponse
   */
  public function filter_grouping_delete(Request $request)
  {
    $grouping = ShopFilterGrouping::find($request->id);
    $category_id = $grouping->filter->spec->group->category_id;

    ShopFilterGrouping::find($request->id)->delete();

    $groups = ShopSpecGroup::where(['category_id' => $category_id])
      ->with('specs')
      ->with('specs.filter')
      ->with('specs.filter.extras')
      ->with('specs.filter.groupings')
      ->orderBy('shop_spec_groups.order')
      ->get();

    return response()->json(['groups' => $groups]);
  }

}
