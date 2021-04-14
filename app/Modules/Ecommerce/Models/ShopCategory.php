<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kalnoy\Nestedset\NodeTrait;

class ShopCategory extends Model
{
  use NodeTrait;

  protected $guarded = [];

  public function image(): HasOne
  {
    return $this->hasOne(ShopImages::class, 'entity_id', 'id')
      ->where('entity', '=', 'category')
      ->orderBy('id', 'desc');
  }

  public function spec_groups(): HasMany
  {
    return $this->hasMany(ShopSpecGroup::class, 'category_id', 'id')
      ->orderBy('order');
  }

  public function products(): HasMany
  {
    return $this->hasMany(ShopProduct::class, 'category_id', 'id');
  }
}
