<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopProduct extends Model
{
  protected $appends = ['last_updated'];
  protected $guarded = [];


  public function getLastUpdatedAttribute()
  {

    $toProduct = ShopProductToStore::select('updated_at')->where(['product_id' => $this->id])->orderBy('updated_at', 'desc')->first();
    $labels = ShopProductToLabelListToStoreLeaf::select('updated_at')->where(['product_id' => $this->id])->orderBy('updated_at', 'desc')->first();
    $specs = ShopProductSpec::select('updated_at')->where(['product_id' => $this->id])->orderBy('updated_at', 'desc')->first();
    $max = max(
      isset($this->updated_at)?$this->updated_at:"",
      isset($toProduct->updated_at)?$toProduct->updated_at:"",
      isset($labels->updated_at)?$labels->updated_at:"",
      isset($specs->updated_at)?$specs->updated_at:""
    );
    if($max){
      $max = $max->format('Y-m-d H:i:s');
    }
      return $max;
  }
  public function category(): HasOne
  {
    return $this->hasOne(ShopCategory::class, 'id', 'category_id');
  }

  public function toProductByStore($store_id): HasOne
  {
    return $this->hasOne(ShopProductToStore::class, 'product_id', 'id')
      ->where('store_id', '=', $store_id);
  }

  public function toProduct(): BelongsToMany
  {
    return $this->belongsToMany(
      'App\Modules\Ecommerce\Models\ShopStoreLeaf',
      'shop_products_to_stores',
      'product_id',
      'store_id')
      ->withPivot('price', 'old_price', 'discount', 'quantity');
  }

  public function images(): HasMany
  {
    return $this->hasMany(ShopImages::class, 'entity_id', 'id')
      ->where('entity', '=','product')->orderBy('order', 'ASC');
  }

  public function thumb(): HasOne
  {
    return $this->hasOne(ShopImages::class, 'entity_id', 'id')
      ->where('entity', '=','product');
  }

  public function specs(): HasMany
  {
    return $this->hasMany(ShopProductSpec::class, 'product_id', 'id');
  }

  public function labelToStoresByStore($store_id): HasMany
  {
    return $this->hasMany(ShopProductToLabelListToStoreLeaf::class, 'product_id', 'id')
      ->with('label')
      ->where('store_id', '=', $store_id);
  }
  public function labelToStores(): HasMany
  {
    return $this->hasMany(ShopProductToLabelListToStoreLeaf::class, 'product_id', 'id');
  }

  public function labels(): HasManyThrough
  {
    return $this->hasManyThrough(
      'App\Modules\Ecommerce\Models\ShopLabelList',
      'App\Modules\Ecommerce\Models\ShopProductToLabelListToStoreLeaf',
      'label_list_id',
      'id',
      'id',
      'id'
    );
  }

  public function union()
  {
    return $this->hasOneThrough(
      'App\Modules\Ecommerce\Models\ShopUnion',
      'App\Modules\Ecommerce\Models\ShopProductToUnion',
      'product_id',
      'id',
      'id',
      'union_id',
      );
  }

}
