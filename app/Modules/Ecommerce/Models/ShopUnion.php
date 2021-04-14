<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ShopUnion extends Model
{

  protected $guarded = [];

  public function products(): HasManyThrough
  {
    return $this->hasManyThrough(
      'App\Modules\Ecommerce\Models\ShopProduct',
      'App\Modules\Ecommerce\Models\ShopProductToUnion',
      'union_id',
      'id',
      'id',
      'product_id',
      );
  }

  public function images(): HasMany
  {
    return $this->hasMany(ShopImages::class, 'entity_id', 'id')
      ->where('entity', '=','union')->orderBy('order','asc');
  }

}
