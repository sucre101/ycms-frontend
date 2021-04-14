<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopCart extends Model
{
  protected $guarded = [];

  public $timestamps = false;

  public function getProductCart(): HasMany
  {
    return $this->hasMany(ShopCartToProduct::class, 'cart_id', 'id');
  }
}
