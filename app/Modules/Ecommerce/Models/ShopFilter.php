<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopFilter extends Model
{

  public function spec(): HasOne
  {
    return $this->hasOne(ShopSpec::class, 'id', 'spec_id');
  }

  public function extras(): HasMany
  {
    return $this->hasMany(ShopFilterExtra::class, 'filter_id', 'id');
  }

  public function groupings(): HasMany
  {
    return $this->hasMany(ShopFilterGrouping::class, 'filter_id', 'id');
  }
}
