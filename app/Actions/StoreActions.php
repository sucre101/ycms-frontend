<?php

namespace App\Actions;

use App\Modules\Ecommerce\ShopCategory;
use App\Modules\Ecommerce\ShopStoreLeaf;
use App\Modules\Ecommerce\ShopStoreStructure;

// TODO: create description for this class

/**
 * Class StoreActions
 * @package App\Actions
 */
class StoreActions
{
  protected $app;
  protected $module;

  public function __construct($module)
  {
    $this->module = $module;
  }

  public function createStructure($post)
  {
    if (!$post->parentId) {
      $this->app->storeStructure()->firstOrCreate([
        'name' => $post->name,
      ]);
    } else {
      $parent = $this->app->storeStructure()->findOrFail($post->parentId);
      $parent->appendNode($this->app->storeStructure()->firstOrCreate(['name' => $post->name]));
    }
  }

  public function updateStructure($post)
  {
    $category = ShopStoreStructure::find($post->id);
    $category->parent_id = $post->parent_id;
    $category->save();

    ShopStoreStructure::rebuildTree($post->target);
  }

  public function deleteElement($id)
  {
    $this->app->storeStructure()->find($id)->delete();
    $this->app->storeStructure()->fixTree();
  }

  /**
   * Method which got params and create store structure with store leaf
   * @param $post
   */
  public function createStore($post): void
  {

    $this->module->eCommerce()->create([
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

//    if (isset($post['structureId'])) {
//      $parent = $this->app->storeStructure->find($post['structureId']);
//
//      $parent->stores()->create([
//        'name' => $post['name'],
//        'email' => $post['email'],
//        'phone' => $post['phone'],
//        'address' => $post['address'],
//        'lat' => $post['lat'],
//        'lon' => $post['lon'],
//        'tax_name' => $post['tax_name'],
//        'tax_rate' => $post['tax_rate'],
//        'zone_id' => 1,
//        'currency_id' => $post['currency'],
//        'app_id' => $this->app['id'],
//        'default' => $this->app->stores->count() < 1,
//      ]);
//    } else {
//
//      $this->app->stores()->create([
//        'name' => $post['name'],
//        'email' => $post['email'],
//        'phone' => $post['phone'],
//        'address' => $post['address'],
//        'lat' => $post['lat'],
//        'lon' => $post['lon'],
//        'tax_name' => $post['tax_name'],
//        'tax_rate' => $post['tax_rate'],
//        'zone_id' => 1,
//        'currency_id' => $post['currency'],
//        'default' => $this->app->stores->count() < 1,
//      ]);
//
//    }

  }
}
