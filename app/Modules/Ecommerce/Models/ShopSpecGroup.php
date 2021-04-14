<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopSpecGroup extends Model
{
  protected $guarded = [];

  public function children(): HasMany
  {
    return $this->hasMany(ShopSpec::class,'group_id','id')->orderBy('order');
  }
  public function specs(): HasMany
  {
    return $this->hasMany(ShopSpec::class,'group_id','id')->orderBy('order');
  }
  public function category(): HasOne
  {
    return $this->hasOne(ShopCategory::class,'id','category_id');
  }
}
