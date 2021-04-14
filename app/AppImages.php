<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppImages extends Model
{
  protected $table = "app_images_to_entity";
  protected $guarded = [];
  protected $appends = ['image_url'];

  /**
   * get images with APP URL
   */
  public function getImageUrlAttribute()
  {
    return url($this->url_original);
  }

  /**
   * save images to server
   */
  public static function save_image($id, $entity, $image, $order = null)
  {

    $imageName = substr($image->getClientOriginalName(), 0, strrpos($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()))
      .'-'.time().'.'.$image->getClientOriginalExtension();
    $image->move(public_path('img/uploads'), $imageName);

    $max_order = AppImages::where([
      'entity' => $entity,
      'entity_id' => $id,
    ])->max('order');

    $newImage = new AppImages();
    $newImage->entity = $entity;
    $newImage->entity_id = $id;
    $newImage->order = $max_order+1;
    $newImage->url_original = '/img/uploads/'.$imageName;
    $newImage->url_medium = '/img/uploads/'.$imageName;
    $newImage->url_small = '/img/uploads/'.$imageName;
    $newImage->save();
  }

  /**
   * save images to server
   */
  public static function delete_image($id){

    if($id){
      $image = AppImages::find($id);
      if($image){
        unlink(public_path($image->url_original));
        $image->delete();
      }
    }
  }
}
