<?php

namespace App\Modules\Ecommerce\Controllers;

use App\Http\Controllers\YcmsController;
use App\Modules\Ecommerce\Models\Category;
use App\Modules\Ecommerce\Models\ShopCategory;
use App\Modules\Ecommerce\Models\ShopImages;
use App\Modules\Ecommerce\Models\ShopLabelList;
use App\Modules\Ecommerce\Models\ShopOrder;
use App\Modules\Ecommerce\Models\ShopProduct;
use App\Modules\Ecommerce\Models\ShopProductSpec;
use App\Modules\Ecommerce\Models\ShopProductToLabelListToStoreLeaf;
use App\Modules\Ecommerce\Models\ShopProductToStore;
use App\Modules\Ecommerce\Models\ShopProductToUnion;
use App\Modules\Ecommerce\Models\ShopSetting;
use App\Modules\Ecommerce\Models\ShopStoreLeaf;
use App\Modules\Ecommerce\Models\ShopUnion;
use App\Services\Modules\Ecommerce\EcommerceService;
use App\Style;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopStoresController extends YcmsController
{

  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function getStores(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'stores' => $this->module->eCommerce,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param $moduleId
   * @param  Request  $request
   * @param  EcommerceService  $ecommerceService
   * @return JsonResponse
   */
  public function store($moduleId, Request $request, EcommerceService $ecommerceService): JsonResponse
  {
    $module = $ecommerceService->createStore($request, $moduleId);

    return response()->json(['success' => true, 'stores' => $module->eCommerce]);
  }

  public function deleteStore($moduleId, Request $request, EcommerceService $ecommerceService): JsonResponse
  {
    $module = $ecommerceService->deleteStore($moduleId, $request->post('store_id'));

    return response()->json(['success' => true, 'stores' => $module->eCommerce]);
  }

  /**
   * @return JsonResponse
   */
  public function getSettings(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'settings' => ShopSetting::where('user_module_id', $this->module->id)->first(),
      'styles' => Style::where('user_module_id', $this->module->id)->first()
    ]);
  }

  /**
   * @param $module_id
   * @param  Request  $request
   * @param  EcommerceService  $ecommerceService
   * @return JsonResponse
   */
  public function saveSettings($module_id, Request $request, EcommerceService $ecommerceService): JsonResponse
  {

    $exa = ['border-radius' => '20', 'background-color' => 'grey', 'border-color' => '#545454'];

    $data['checkout_button'] = $exa;

    Style::where('user_module_id', $module_id)->update(['data' => \GuzzleHttp\json_encode($data)]);

    dd($module_id);

    $ecommerceService->updateSettingsData($request->post());

    return response()->json(['success' => true, 'data' => $request->post()]);
  }

  /**
   * @return JsonResponse
   */
  public function getCategories(): JsonResponse
  {
    $categories = ShopCategory::where('user_module_id', $this->module->id)->get()->toTree();

    return response()->json(['success' => true, 'categories' => $categories]);
  }

  public function getCategory($module_id, $category_id)
  {
    $category = ShopCategory::where('id', $category_id)
      ->with('image')
      ->with('spec_groups.specs')
      ->first();


    return response()->json([
      'success' => true,
      'category' => $category,
      'list' => ShopCategory::where('user_module_id', $module_id)->where('id', '!=', $category_id)->get()->toFlatTree()
    ]);
  }

  public function createCategory(Request $request): JsonResponse
  {
    Category::create(['name' => $request->name, 'user_module_id' => $this->module->id]);

    return $this->getCategories();
  }

  /**
   * @param $module_id
   * @param  Request  $request
   * @param  EcommerceService  $ecommerceService
   * @return JsonResponse
   */
  public function updateCategory($module_id, Request $request, EcommerceService $ecommerceService)
  {
    $ecommerceService->updateCategoryData($request, $module_id);

    return response()->json(['success' => true]);
  }

  /**
   * Rebuild category tree.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function rebuildCategoryTree(Request $request): JsonResponse
  {
    $category = ShopCategory::find($request->id);
    $category->parent_id = $request->parent_id;
    $category->save();

    ShopCategory::rebuildTree($request->target);

    return response()->json(['success' => true]);
  }

  /**
   * @return JsonResponse
   */
  public function getProducts(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'products' => ShopProduct::where('user_module_id', $this->module->id)->take(20)->get(),
      'categories' => ShopCategory::where('user_module_id', $this->module->id)->get()->toFlatTree()
    ]);
  }

  /**
   * @param $module_id
   * @param $limit
   * @param $offset
   * @return JsonResponse
   */
  public function afterLoadProduct($module_id, $limit, $offset = 20): JsonResponse
  {
    return response()->json([
      'success' => true,
      'products' => ShopProduct::where('user_module_id', $this->module->id)->skip($offset)->take($limit)->get()
    ]);
  }

  /**
   * @param $module_id
   * @param $productId
   * @return JsonResponse
   */
  public function getProduct($module_id, $productId): JsonResponse
  {
    $labels = ShopLabelList::where('user_module_id', $module_id)->get();
    $stores = ShopStoreLeaf::where('user_module_id', $module_id)->get();
    $unions = ShopUnion::where('user_module_id', $module_id)->get();

    $product = ShopProduct::where('id', $productId)
      ->with('category.spec_groups.specs', 'specs', 'images', 'toProduct', 'labelToStores.label', 'union.products')
      ->first();

    if ($product === null) {
      return response()->json(['success' => false, 'error' => 'Product not found']);
    }

    return response()->json([
      'success' => true,
      'product' => $product,
      'labels' => $labels,
      'stores' => $stores,
      'unions' => $unions,
    ]);
  }

  /**
   * @param  Request  $request
   * @param  EcommerceService  $ecommerceService
   * @return JsonResponse
   */
  public function createProduct(Request $request, EcommerceService $ecommerceService): JsonResponse
  {
    $product = $ecommerceService->createProductData($request->post());

    return response()->json(['success' => true, 'product' => $product]);
  }

  /**
   * @param $module_id
   * @param  Request  $request
   * @param  EcommerceService  $ecommerceService
   * @return JsonResponse
   */
  public function updateProduct($module_id, Request $request, EcommerceService $ecommerceService): JsonResponse
  {
    $ecommerceService->updateProductData($request, $module_id);

    return $this->getProduct($module_id, $request->post('id'));
  }

  /**
   * @param $module_id
   * @param $product_id
   * @param  EcommerceService  $ecommerceService
   * @return JsonResponse
   */
  public function productDuplicate($module_id, $product_id, EcommerceService $ecommerceService): JsonResponse
  {
    $product = $ecommerceService->cloneProduct($product_id);

    return response()->json(['product' => $product]);
  }

  /**
   * @param $module_id
   * @param $product_id
   * @return JsonResponse
   */
  public function productDelete($module_id, $product_id): JsonResponse
  {
    ShopProduct::where('id', $product_id)->delete();

    ShopImages::where('entity_id', $product_id)->delete();

    return response()->json(['success' => true]);
  }

  /**
   * @param $module_id
   * @param $product_id
   * @param  Request  $request
   * @return JsonResponse
   */
  public function saveImage($module_id, $product_id, Request $request): JsonResponse
  {
    return response()->json(['success' => true]);
  }

  /**
   * @param $moduleId
   * @return JsonResponse
   */
  public function getAllOrders($moduleId): JsonResponse
  {
    $orders = ShopOrder::where('user_module_id', $moduleId)->with('store', 'status')->get();

    return response()->json(['success' => true, 'orders' => $orders]);
  }

  /**
   * @param $moduleId
   * @param $orderId
   * @return JsonResponse
   */
  public function getOrder($moduleId, $orderId): JsonResponse
  {
    return response()->json(['success' => true, 'order' => ShopOrder::where('id', $orderId)->with('cartToProduct.product', 'store.currency')->first()]);
  }

  /**
   * @param $moduleId
   * @param  Request  $request
   * @param  EcommerceService  $ecommerceService
   */
  public function updateOrder($moduleId, Request $request, EcommerceService $ecommerceService): void
  {
    $ecommerceService->updateOrderData($request->post());
  }

  /**
   * @param $module_id
   * @return JsonResponse
   */
  public function getUnions($module_id): JsonResponse
  {
    return response()->json(['success' => true, 'unions' => ShopUnion::where('user_module_id', $module_id)->get()]);
  }

  public function createUnion($module_id, Request $request)
  {
    ShopUnion::create([
      'user_module_id' => $module_id,
      'name' => $request->post('name'),
      'desc' => ''
    ]);

    return response()->json(['success' => true]);
  }

  /**
   * @param  Request  $request
   * @return JsonResponse
   */
  public function updateUnion(Request $request): JsonResponse
  {
    ShopUnion::where('id', $request->id)->update(['desc' => $request->desc ?? '', 'name' => $request->name]);

    return response()->json(['success' => true]);
  }

  public function deleteUnion($module_id, $union_id)
  {
    ShopUnion::where('id', $union_id)->delete();

    return response()->json(['success' => true]);
  }

}
