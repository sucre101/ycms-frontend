<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopProductToStore extends Model
{
  protected $table = 'shop_products_to_stores';

  protected $guarded = [];

//  public $timestamps = false;

  public function inStores(): HasMany
  {
    return $this->hasMany(ShopStoreLeaf::class, 'id', 'store_id');
  }

  public function product(): HasOne
  {
    return $this->hasOne(ShopProduct::class, 'id', 'product_id');
  }

}
