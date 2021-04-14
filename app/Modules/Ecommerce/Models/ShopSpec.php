<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopSpec extends Model
{
  protected $guarded = [];

  public function spec_data($product_id = null)
  {
    return $this->hasMany(ShopProductSpec::class,'spec_id','id')
      ->where(['product_id' => $product_id])->first();
  }

  public function spec_datas(): HasOne
  {
    return $this->hasOne(ShopProductSpec::class,'spec_id','id');
  }

  public function filter(): HasOne
  {
    return $this->hasOne(ShopFilter::class,'spec_id','id');
  }

  public function group(): HasOne
  {
    return $this->hasOne(ShopSpecGroup::class,'id','group_id');
  }

  public function unit(): HasOne
  {
    return $this->hasOne(ShopUnit::class,'id','unit_id');
  }


  public function spec_data_values(): array
  {
    $data_array = [];
    $data = $this->spec_datas()->get();
    foreach ($data as $d){
      foreach ($d->data as $ddata){
        if(!in_array($ddata, $data_array, true)){
          $data_array[] = $ddata;
        }
      }
    }
    sort($data_array);
    return $data_array;
  }
}
