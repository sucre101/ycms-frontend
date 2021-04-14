<?php

namespace App;

use App\Modules\Ecommerce\Models\ShopCategory;
use App\Modules\Ecommerce\Models\ShopLabelList;
use App\Modules\Ecommerce\Models\ShopStoreLeaf;
use App\Modules\PageBuilder\Block;
use Illuminate\Database\Eloquent\Model;

class UserModule extends Model
{

  protected $table = 'user_module';
  protected $guarded = [];

  public function module()
  {
    return $this->hasOne(Module::class, 'id', 'module_id');
  }

  public function eCommerce()
  {
    return $this->hasMany(ShopStoreLeaf::class, 'user_module_id', 'id');
  }

  public function ECategories()
  {
    return $this->hasMany(ShopCategory::class, 'user_module_id', 'id');
  }

  public function eCommerceLabels()
  {
    return $this->hasMany(ShopLabelList::class, 'user_module_id', 'id');
  }

  public function pb_blocks()
  {
    return $this->hasMany(Block::class, 'page_id', 'id')->orderBy('order')->orderBy('s_order');
  }

  public function pb_active_blocks()
  {
    return $this->hasMany(Block::class, 'page_id', 'id')
      ->where(['is_deleted' => false])->orderBy('order')->orderBy('s_order');
  }

  public function styles()
  {
    return $this->hasOne(Style::class);
  }

}
