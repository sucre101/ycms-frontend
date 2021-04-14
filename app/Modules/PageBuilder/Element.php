<?php

namespace App\Modules\PageBuilder;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
  protected $table = "pb_elements";
  protected $guarded = [];


  public function block(){
    return $this->hasOne(Block::class, 'id', 'page_id');
  }

  public function template(){
    return $this->hasOne(Template::class, 'id', 'template_id');
  }

  public function images()
  {
    return $this->hasMany(Image::class, 'entity_id', 'id')->orderBy('order', 'asc');
  }
}
