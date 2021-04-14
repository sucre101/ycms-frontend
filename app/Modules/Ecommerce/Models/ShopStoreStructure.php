<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kalnoy\Nestedset\NodeTrait;

class ShopStoreStructure extends Model
{
  use NodeTrait;

  protected $guarded = [];

  protected $hidden = ['pivot'];

  public function app(): HasOne
  {
    return $this->hasOne('App\App');
  }

  public function stores(): BelongsToMany
  {
    return $this->belongsToMany(
      'App\Modules\Ecommerce\Models\ShopStoreLeaf',
      'shop_stores_to_structure',
      'structure_id',
      'store_id'
    )->orderBy('order', 'asc');
  }
}
