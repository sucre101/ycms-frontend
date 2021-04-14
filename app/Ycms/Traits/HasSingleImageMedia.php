<?php

namespace App\Ycms\Traits;

// TODO: delete? conflicts with HasMediaTrait

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


trait HasSingleImageMedia
{
   use HasMediaTrait;

  public function getImageUrlAttribute()
  {
    return url($this->getFirstMediaUrl());
  }

  public function registerMediaCollections()
  {
    $this->addMediaCollection('image')->useDisk('media-lib')->singleFile();
  }
}