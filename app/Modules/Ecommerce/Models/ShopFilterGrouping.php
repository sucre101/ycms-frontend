<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopFilterGrouping extends Model
{

  public function filter(): HasOne
  {
    return $this->hasOne(ShopFilter::class, 'id', 'filter_id');
  }
}
