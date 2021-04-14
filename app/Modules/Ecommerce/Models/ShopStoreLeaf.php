<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ShopStoreLeaf
 * @package App
 * @property int id
 * @property
 */
class ShopStoreLeaf extends Model
{
  protected $table = 'shop_stores';

  protected $guarded = [];

  public function structure(): BelongsToMany
  {
    return $this->belongsToMany(
      'App\Modules\Ecommerce\Models\ShopStoreStructure',
      'shop_stores_to_structure',
      'store_id',
      'structure_id'
    );
  }

  public function labels(): HasMany
  {
    return $this->hasMany(ShopProductToLabelListToStoreLeaf::class, 'store_id', 'id');
  }

  public function currency(): HasOne
  {
    return $this->hasOne(ShopCurrency::class, 'id', 'currency_id');
  }

  public function toProduct(): BelongsToMany
  {
    return $this->belongsToMany(
      'App\Modules\Ecommerce\Models\ShopProduct',
      'shop_products_to_stores',
      'store_id',
      'product_id')
      ->withPivot('price', 'old_price', 'discount', 'quantity')
      ->as('price')
      ->with('specs.spec')
      ->with('images');
  }

  public function createData()
  {
    return self::create([
      'name' => 'shop #',
      'email' => 'example@gmail.com',
      'phone' => '',
      'order' => 99,
    ]);
  }
}
