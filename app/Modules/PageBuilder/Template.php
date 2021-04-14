<?php

namespace App\Modules\PageBuilder;

use App\App;
use App\UserModule;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
  protected $table = "pb_style_templates";
  protected $guarded = [];

  protected $appends = ['style'];

  public function getStyleAttribute(){
    $style = "";

    $style .= $this->padding?"padding: $this->padding;":"";
    $style .= $this->border_width?"border-width: $this->border_width;":"";
    $style .= $this->border_type?"border-style: $this->border_type;":"";
    $style .= $this->border_color?"border-color: $this->border_color;":"";
    $style .= $this->border_radius?"border-radius: $this->border_radius;":"";
    $style .= $this->height?"height: $this->height;":"";
    $style .= $this->color?"color: $this->color;":"";
    $style .= $this->bg_color?"background-color: $this->bg_color;":"";
    $style .= $this->overflow?"overflow: $this->overflow;":"";
    $style .= $this->text_shadow?"text-shadow: $this->text_shadow;":"";
    $style .= $this->box_shadow?"box-shadow: $this->box_shadow;":"";
    $style .= $this->text_align?"text-align: $this->text_align;":"text-align:left;";

    return $style;
  }
  public function user_module(){
    return $this->hasOne(UserModule::class,'user_module_id', 'id');
  }
}
