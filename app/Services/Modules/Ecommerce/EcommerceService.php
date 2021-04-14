<?php

namespace App\Services\Modules\Ecommerce;

use App\Modules\Ecommerce\Models\ShopCategory;
use App\Modules\Ecommerce\Models\ShopImages;
use App\Modules\Ecommerce\Models\ShopProductToUnion;
use App\Modules\Ecommerce\Models\ShopSetting;
use App\Modules\Ecommerce\Models\ShopCartToProduct;
use App\Modules\Ecommerce\Models\ShopProduct;
use App\Modules\Ecommerce\Models\ShopProductSpec;
use App\Modules\Ecommerce\Models\ShopProductToLabelListToStoreLeaf;
use App\Modules\Ecommerce\Models\ShopProductToStore;
use App\Modules\Ecommerce\Models\ShopSpec;
use App\Modules\Ecommerce\Models\ShopSpecGroup;
use App\UserModule;

class EcommerceService
{

  protected $requestLabels;
  protected $product;
  protected $productStorage;
  protected $module_id;
  protected $category;
  protected $specs;
  protected $order;
  protected $settings;
  protected $productGallery;
  protected $productUnion;

  public function createStore($post, $module_id)
  {
    $module = UserModule::where('id', $module_id)->first();

    if ($module) {
      $module->eCommerce()->create([
        'user_module_id' => $post['uModuleId'],
        'name' => $post['name'],
        'email' => $post['email'],
        'phone' => $post['phone'],
        'address' => $post['address'],
        'lat' => $post['lat'],
        'lon' => $post['lon'],
        'tax_name' => $post['tax_name'],
        'tax_rate' => $post['tax_rate'],
        'zone_id' => 1,
        'currency_id' => $post['currency'],
        'default' => false,
      ]);
    }

    return $module;
  }

  /**
   * @param $moduleId
   * @param $storeId
   * @return mixed
   */
  public function deleteStore($moduleId, $storeId)
  {
    $module = UserModule::where('id', $moduleId)->first();
    $module->eCommerce()->where('id', $storeId)->delete();

    return $module;
  }

  /**
   * @param $data
   * @return ShopProduct
   */
  public function createProductData(array $data): ShopProduct
  {
    return ShopProduct::create([
      'name' => $data['name'],
      'category_id' => $data['category'],
      'user_module_id' => $data['module'],
      'desc' => '',
      'sku' => ''
    ]);
  }

  public function updateProductData($data, $module_id): void
  {
    $this->module_id = $module_id;

    $this->product = $data;
    $this->productGallery = $data['images'];
    $this->productUnion = $data['union'];

    $this->updateBaseProduct();

    if ($data['to_product']) {
      $this->productStorage = $this->product['to_product'];
      unset($this->product['to_product']);
      $this->updateProductStorage();
    }

    if ($this->product['label_to_stores']) {
      $this->requestLabels = $this->product['label_to_stores'];
      unset($this->product['label_to_stores']);
      $this->updateLabels();
    }

    if ($data['specs']) {
      $this->specs = $data['specs'];
      unset($data['specs']);
      $this->updateProductSpecs();
    }

    $this->updateProductUnion();

    $this->updateProductGallery();

  }

  protected function updateProductStorage(): void
  {
    if ($this->productStorage) {

      foreach ($this->productStorage as $store) {
        ShopProductToStore::updateOrCreate(
          [
            'store_id' => $store['id'],
            'product_id' => $this->product['id'],
            'user_module_id' => $this->module_id
          ],
          [
            'price' => $store['pivot']['price'],
            'old_price' => $store['pivot']['old_price'],
            'discount' => $store['pivot']['discount'],
            'quantity' => $store['pivot']['quantity']
          ]
        );
      }
    }
  }

  protected function updateBaseProduct(): void
  {
    $product = ShopProduct::where('id', $this->product['id'])->first();

    if ($product) {
      $product->category_id = $this->product['category_id'];
      $product->name = $this->product['name'];
      $product->desc = $this->product['desc'] ?? '';
      $product->save();
    }
  }

  protected function updateProductSpecs(): void
  {
    foreach ($this->specs as $spec) {

      if (!isset($spec['id'])) {

        ShopProductSpec::create([
          'spec_id' => $spec['spec_id'],
          'product_id' => $this->product['id'],
          'order' => 999,
          'data' => \GuzzleHttp\json_encode($spec['data']),
        ]);

      } else {

        $productSpec = ShopProductSpec::where('id', $spec['id'])->first();

        if ($productSpec) {

          $productSpec->data = $spec['data'];
          $productSpec->save();

        }

      }

    }
  }

  protected function updateLabels(): void
  {
    ShopProductToLabelListToStoreLeaf::where('product_id', $this->product['id'])->delete();

    foreach ($this->requestLabels as $item) {

      ShopProductToLabelListToStoreLeaf::firstOrCreate([
        'product_id' => $item['product_id'],
        'store_id' => $item['store_id'],
        'label_list_id' => $item['label_list_id']
      ]);

    }
  }

  public function updateCategoryData($category, $module_id): void
  {
    $this->category = ShopCategory::where('id', $category['id'])->first();

    $this->category->description = $category['description'];

    if ($category['parent_id'] === null) {

      $this->category->saveAsRoot();

    } else {

      $parent = ShopCategory::where('user_module_id', $module_id)->where('id', $category['parent_id'])->first();

      $parent->appendNode($this->category);
    }


    $specGroups = $category->spec_groups;
    $this->updateCategorySpecs($specGroups, $category['id']);

  }

  protected function updateCategorySpecs($specGroups, $categoryId): void
  {
    $staySpecGroups = $staySpecs = [];

    foreach ($specGroups as $group) {
      if (!isset($group['id'])) {

        $newSpecGroup = ShopSpecGroup::create([
          'name' => $group['name'],
          'category_id' => $categoryId,
          'order' => 999
        ]);

        $staySpecGroups[] = $newSpecGroup['id'];

        if ($group['specs'] && count($group['specs']) > 0) {

          foreach ($group['specs'] as $spec) {

            if (!isset($spec['id'])) {

              $newSpec = ShopSpec::create([
                'name' => $iSpec['name'],
                'group_id' => $newSpecGroup['id'],
                'order' => 999,
                'unit_id' => 1
              ]);

              $staySpecs[] = $newSpec['id'];

            }

          }

        }

      } else {

        $staySpecGroups[] = $group['id'];

        foreach ($group['specs'] as $iSpec) {

          if (!isset($iSpec['id'])) {

            $newSpec = ShopSpec::create([
              'name' => $iSpec['name'],
              'group_id' => $group['id'],
              'order' => 999,
              'unit_id' => 1
            ]);

            $staySpecs[] = $newSpec['id'];

          } else {

            $staySpecs[] = $iSpec['id'];

          }

        }

      }
    }

    if (count($staySpecGroups)) {
      ShopSpecGroup::whereNotIn('id', $staySpecGroups)->delete();
    }
    if (count($staySpecs)) {
      ShopSpec::whereNotIn('id', $staySpecs)->delete();
    }

  }

  public function updateOrderData($order): void
  {
    $this->order = $order;
    $this->updateOrderProductsQuantity();
  }

  protected function updateOrderProductsQuantity(): void
  {
    foreach ($this->order['cart_to_product'] as $order) {
      ShopCartToProduct::where('id', $order['id'])
        ->update([
          'quantity' => $order['quantity'],
          'price' => $order['price']
        ]);
    }
  }

  public function updateSettingsData($settings): void
  {
    $this->settings = $settings;

    ShopSetting::where('id', $this->settings['id'])
      ->update([
        'store_structure' => $settings['store_structure'],
        'template_id' => $settings['template_id']
      ]);
  }

  protected function updateProductGallery(): void
  {
    ShopImages::where('entity_id', $this->product['id'])->delete();

    if ($this->productGallery) {
      foreach ($this->productGallery as $key => $image) {

        ShopImages::create([
          'entity' => 'product',
          'entity_id' => $image['entity_id'],
          'order' => $image['order'],
          'url_original' => $image['image_url'],
          'url_medium' => $image['image_url'],
          'url_small' => $image['image_url'],
        ]);

      }
    }
  }

  /**
   * @param $product_id
   * @return mixed
   */
  public function cloneProduct($product_id): ShopProduct
  {
    $product = ShopProduct::where('id', $product_id)->with('category')->first();

    $newProduct = $product->replicate();
    $newProduct->name = "copy $product->name";
    $newProduct->save();

    //copy images
    $images = ShopImages::where(["entity_id" => $product->id, "entity" => "product"])->get();

    foreach ($images as $image) {

      ShopImages::create([
        'entity' => 'product',
        'entity_id' => $image['entity_id'],
        'order' => 99,
        'url_original' => $image['image_url'],
        'url_medium' => $image['image_url'],
        'url_small' => $image['image_url'],
      ]);

    }

    //copy spec_data
    $spec_datas = ShopProductSpec::where(['product_id' => $product->id])->get();
    foreach ($spec_datas as $spec_data){
      $new_spec_data = $spec_data->replicate();
      $new_spec_data->product_id = $newProduct->id;
      $new_spec_data->save();
    }

    //copy store
    $stores = ShopProductToStore::where(['product_id' => $product->id])->get();
    foreach ($stores as $store){
      $new_store = $store->replicate();
      $new_store->product_id = $newProduct->id;
      $new_store->save();
    }

    //copy union
    $store = ShopProductToUnion::where(['product_id' => $product->id])->first();


    if ($store) {
      ShopProductToUnion::create([
        'product_id' => $product->id,
        'union_id' => $store->union_id
      ]);
    }

    //copy labels
    $labels = ShopProductToLabelListToStoreLeaf::where(['product_id' => $product->id])->get();
    foreach ($labels as $label){
      $new_label = $label->replicate();
      $new_label->product_id = $newProduct->id;
      $new_label->save();
    }

    return $newProduct;
  }

  public function updateProductUnion(): void
  {
    if ($this->productUnion) {
      ShopProductToUnion::updateOrCreate(
        ['product_id' => $this->product['id']],
        ['union_id' => $this->productUnion['id']]
      );
    } else {
      ShopProductToUnion::where('product_id', $this->product['id'])->delete();
    }
  }

}
