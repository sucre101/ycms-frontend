<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
// TODO: delete media files with model

class Product extends Model implements HasMedia
{
  use HasMediaTrait;

  protected $guarded = [];

  protected $appends = ['imageUrl'];

  public function getImageUrlAttribute()
  {
    return url($this->getFirstMediaUrl('image','thumb'));
  }

  public function registerMediaCollections()
  {
    $this->addMediaCollection('image')->useDisk('media-lib')->singleFile();
  }

  public function registerMediaConversions(Media $media = null)
  {
    $this->addMediaConversion('thumb')->width(200);
    $this->addMediaConversion('norm')->width(320);
  }
  //                        / Spatie/MediaLibrary

  public function category()
  {
    return $this->belongsTo(Category::class);
  }
}
