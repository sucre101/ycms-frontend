<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopCartToProduct extends Model
{
  protected $guarded = [];

  public $timestamps = false;

  public function product(): HasOne
  {
    return $this->hasOne(ShopProduct::class, 'id', 'product_id');
  }

  public function productToStore(): HasOne
  {
    return $this->hasOne(ShopProductToStore::class, 'product_id', 'product_id');
  }
}
