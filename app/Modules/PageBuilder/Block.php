<?php

namespace App\Modules\PageBuilder;

use App\UserModule;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
  protected $table = "pb_blocks";
  protected $guarded = [];

  public function user_module(){
    return $this->hasOne(UserModule::class, 'id', 'user_module_id');
  }

  public function template(){
    return $this->hasOne(Template::class, 'id', 'template_id');
  }

  public function elements(){
    return $this->hasMany(Element::class, 'block_id', 'id')->with('images')->orderBy('id');
  }

}
