<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopProductToLabelListToStoreLeaf extends Model
{
  protected $guarded = [];

  protected $table = 'shop_product_to_label_list_to_store_leaf';

//  public $timestamps = false;

  public function label(): HasOne
  {
    return $this->hasOne(ShopLabelList::class, 'id', 'label_list_id');
  }

  public function products(): HasMany
  {
    return $this->hasMany(ShopProduct::class, 'id', 'product_id');
  }
}
