<?php

namespace App\Ycms\Traits;

// TODO: delete? conflicts with WithMediaTrait
trait HasConvertionsThumb200Norm320
{
  public function registerMediaConversions()
  {
    $this->addMediaConversion('thumb')->width(200);
    $this->addMediaConversion('norm')->width(320);
  }
}