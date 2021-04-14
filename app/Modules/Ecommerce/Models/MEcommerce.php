<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use App\Page;

class MEcommerce extends Model
{
  public function page()
  {
    return $this->morphOne(Page::class, 'module');
  }

  public function shops()
  {
    return $this->hasMany(Shop::class);
  }
}
