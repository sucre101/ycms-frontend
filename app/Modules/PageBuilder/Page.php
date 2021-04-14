<?php

namespace App\Modules\PageBuilder;

use App\App;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  protected $table = "pb_pages";
  protected $guarded = [];

  public function app(){
    return $this->hasOne(App::class);
  }

  public function template(){
    return $this->hasOne(Template::class, 'id', 'template_id');
  }

  public function blocks(){
    return $this->hasMany(Block::class, 'page_id', 'id')->orderBy('order')->orderBy('s_order');
  }

  public function active_blocks(){
    return $this->hasMany(Block::class, 'page_id', 'id')
      ->where(['is_deleted' => false])->orderBy('order')->orderBy('s_order');
  }
}
