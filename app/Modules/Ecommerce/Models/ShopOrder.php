<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopOrder extends Model
{
  protected $guarded = [];

  public function products(): BelongsToMany
  {
    return $this->belongsToMany(
      'App\Modules\Ecommerce\Models\ShopProduct',
      'shop_cart_to_products',
      'cart_id',
      'product_id'
    )->withPivot('price', 'quantity', 'store_id');
  }

  public function cartToProduct(): HasMany
  {
    return $this->hasMany(ShopCartToProduct::class, 'cart_id', 'cart_id');
  }

  public function status(): HasOne
  {
    return $this->hasOne(ShopOrderStatus::class, 'id', 'order_status_id');
  }

  public function store(): HasOne
  {
    return $this->hasOne(ShopStoreLeaf::class, 'id', 'store_id');
  }
}
