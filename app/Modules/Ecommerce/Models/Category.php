<?php
namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
// TODO: delete media files with model
class Category extends Model implements HasMedia
{
  use NodeTrait;

  protected $table = 'shop_categories';
  protected $guarded = ['id'];
  protected $appends = ['imageUrl'];

  //                         Spatie/MediaLibrary
  use HasMediaTrait;

  public function getImageUrlAttribute()
  {
    return url($this->getFirstMediaUrl('image','thumb'));
  }

  public function registerMediaCollections()
  {
    $this->addMediaCollection('image')->useDisk('media-lib')->singleFile()
      ->useFallbackUrl('/img/no-image.png')
      ->useFallbackPath(public_path('/img/no-image.png'));
  }

  public function registerMediaConversions(Media $media = null)
  {
    $this->addMediaConversion('thumb')->width(200);
    $this->addMediaConversion('norm')->width(320);
  }
  //                        / Spatie/MediaLibrary

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function products()
  {
    return $this->hasMany(Product::class);
  }

  // FIXME: might be refactored using #clonechildrento?
  public function cloneTo(array $newParentIds)
  {
    $this::find($newParentIds)->each(function($newParent) {
      if(!$newParent->children->contains($this)) {
        $clone = $this->replicate();
        $newParent->appendNode($clone);
        if ($this->media->count()) {
          $clone->addMediaFromUrl($this->imageUrl)->toMediaCollection();
        }
        $this->cloneChildrenTo($clone);
      }
    });

    if(!in_array($this->parent->id, $newParentIds)) {
      $this->delete();
    }
  }

  private function cloneChildrenTo($dest)
  {
    if ($children = $this->children) {
      $children->each(function($child) use ($dest) {
        $clone = $child->replicate();
        $dest->appendNode($clone);
        if ($child->media->count()) {
          $clone->addMediaFromUrl($child->imageUrl)->toMediaCollection();
        }
        $child->cloneChildrenTo($clone);
      });
    }
  }
}
