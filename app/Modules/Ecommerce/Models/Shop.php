<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
  protected $guarded = ['id'];

  public function mEcommerce(): BelongsTo
  {
    return $this->belongsTo(MEcommerce::class);
  }

  public function categories(): HasMany
  {
    return $this->hasMany(Category::class);
  }

  public function isBelongsToApp($app): bool
  {
    // FIXME: must be more efficient solution
    return $this->mEcommerce->page->app->id == $app->id;
  }
}
