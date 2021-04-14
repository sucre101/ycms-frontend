<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopProductSpec extends Model
{
  protected $table = "shop_products_specs_data";

  protected $guarded = [];

  /**
   * Get the spec data.
   *
   * @param  string  $value
   * @return string
   */
  public function getDataAttribute($value): string
  {
    return json_decode($value);
  }

  public function spec(): HasOne
  {
    return $this->hasOne(ShopSpec::class,'id','spec_id');
  }

  public function product(): HasOne
  {
    return $this->hasOne(ShopProduct::class,'id','product_id');
  }

}
