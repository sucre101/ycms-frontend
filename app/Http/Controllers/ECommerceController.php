<?php

namespace App\Http\Controllers;

use App\Actions\StoreActions;
use App\Modules\Ecommerce\ShopCurrency;
use App\Modules\Ecommerce\ShopStoreLeaf;
use App\Modules\Ecommerce\ShopStoresToStructure;
use App\Modules\Ecommerce\ShopStoreStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ECommerceController extends YcmsController
{

  /**
   * Method which return view with list store with Store structures
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function list()
  {
    $app = $this->getApp();
    $flatStructure = $app->storeStructure()->get(['id', 'name']);
    $currencies = ShopCurrency::all();

    return view('stores.list', compact('app', 'currencies', 'flatStructure'));
  }

  public function showStructure()
  {
    $app = $this->getApp();
    $structure = $app->storeStructure()->get()->toTree();
    $flatStructure = $app->storeStructure()->get(['id', 'name']);

    return view('stores.structure', compact('app', 'structure', 'flatStructure'));
  }

  /**
   * Method which create or add new structure element
   * @param Request $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function newStructure(Request $post)
  {
    (new StoreActions($this->getApp()))->createStructure($post);

    $app = $this->getApp();
    $structure = $app->storeStructure()->get()->toTree();

    return response()->json(['success' => true, 'structure' => $structure]);
  }

  /**
   * Method which update structure
   * @param $slug
   * @param  Request  $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function updateStructure($slug, Request $post)
  {
    (new StoreActions($this->getApp()))->updateStructure($post);

    return response()->json(['success' => true]);
  }

  /**
   * Method which delete structure element
   * @param Request $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteElement(Request $post)
  {
    (new StoreActions($this->getApp()))->deleteElement($post->id);

    return response()->json(['success' => true, 'stores' => $this->getApp()->storeStructure->toTree()]);
  }

  /**
   * Method which rename structure element
   * @param Request $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function editNameStructureElement(Request $post)
  {
    $this->getApp()->storeStructure()->find($post->id)->update(['name' => $post->value]);

    $app = $this->getApp();
    $structure = $app->storeStructure()->get()->toTree();

    return response()->json(['success' => true, 'structure' => $structure]);
  }

  /**
   * Method which create new store with structure
   * @param Request $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function createStore(Request $post)
  {
    (new StoreActions($this->getApp()))->createStore($post->data);

    if ($post->structure) {
      $stores = $this->getApp()->storeStructure->toTree();
    } else {
      $stores = $this->getApp()->stores;
    }

    return response()->json(['success' => true, 'stores' => $stores]);
  }

  /**
   * Method which return view with current store for edit
   * @param $slug
   * @param $store_id
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function editStore($slug, $store_id)
  {
    $store = ShopStoreLeaf::where('id', $store_id)->with('structure')->first();
    $app = $this->getApp();
    $structure = $this->getApp()->storeStructure()->get();
    $currencies = ShopCurrency::all();

    return view('stores.edit', compact('store', 'app', 'structure', 'currencies'));
  }

  public function updateStoreOrder($slug, Request $post)
  {
    $i = 1;
    foreach ($post->post() as $store) {
      ShopStoreLeaf::where('id', $store)->update(['order' => $i]);
      $i++;
    }

    return response()->json(['success' => true]);
  }


  /**
   * Method which save/update store data by id
   * @param Request $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function saveEditedStore(Request $post)
  {
    $data = Arr::except($post->post(), ['id', 'created_at', 'updated_at', 'structure']);

    $store = ShopStoreLeaf::updateOrCreate(['id' => $post->id], $data);

    if (!is_null($post->structure[0]['id'])) {
      ShopStoresToStructure::create(['store_id' => $post->id, 'structure_id' => $post->structure[0]['id']]);
    } else {
      $structure = ShopStoresToStructure::where('store_id', $post->id)->first();
      if ($structure) {
        $structure->delete();
        return response()->json(['success' => true, 'store' => $store]);
      }
    }

    return response()->json(['success' => true, 'store' => $store]);
  }

  /**
   * Method wich copy store with copy prefix
   * @param $slug
   * @param  Request  $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function copyStore($slug, Request $post)
  {
    $store = ShopStoreLeaf::where('id', $post->id)->where('app_id', $this->getApp()->id)->first();

    $newStore = $store->replicate();
    $newStore->name = "copy $store->name";
    $newStore->save();

    return response()->json(['success' => true, 'stores' => $this->getApp()->stores]);
  }

  public function setDefault($slug, Request $post)
  {
    $app = $this->getApp();
    $app->stores()->update(['default' => false]);

    $app->stores()->where('id', $post->id)->update(['default' => true]);

    return response()->json(['success' => true, 'stores' => $this->getApp()->stores]);
  }

  /**
   * Method which delete store by id
   * @param Request $post
   * @return \Illuminate\Http\JsonResponse
   */
  public function deleteStore(Request $post)
  {
    $store = ShopStoreLeaf::find($post->id);

    if ($store->default === true) {
      $this->getApp()->stores()->first()->update(['default' => true]);
    }

    $store->delete();

    return response()->json(['success' => true, 'stores' => $this->getApp()->stores]);
  }

}
