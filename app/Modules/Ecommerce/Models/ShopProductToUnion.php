<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProductToUnion extends Model
{
  protected $table = "shop_products_to_union";

  protected $guarded = [];

//  public function product()
//  {
//    return $this->hasOne('App\Modules\Ecommerce\ShopProduct', 'id', 'product_id');
//  }
//
//  public function union()
//  {
//    return $this->hasOne('App\Modules\Ecommerce\ShopUnion', 'id', 'union_id');
//  }

}
