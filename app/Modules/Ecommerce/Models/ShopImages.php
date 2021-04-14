<?php

namespace App\Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class ShopImages extends Model
{
  protected $table = "shop_images_to_entity";
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
  public static function save_image($id, $entity, $image, $order = null){

    $imageName = substr($image->getClientOriginalName(), 0, strrpos($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension()))
      .'-'.time().'.'.$image->getClientOriginalExtension();
    $image->move(public_path('img/uploads'), $imageName);

    $max_order = ShopImages::where([
      'entity' => $entity,
      'entity_id' => $id,
    ])->max('order');

    $newImage = new self();
    $newImage->entity = $entity;
    $newImage->entity_id = $id;
    $newImage->order = $max_order+1;
    $newImage->url_original = '/img/uploads/'.$imageName;
    $newImage->url_medium = '/img/uploads/'.$imageName;
    $newImage->url_small = '/img/uploads/'.$imageName;
    $newImage->save();
  }

  /**
   * change image data
   */
  public static function change_image($id, $entity, $entity_id, $order): bool
  {
    $image = self::find($id);

    if ($image) {

      if ($entity_id !== $image->entity_id) {

        $siblings = self::where('order', '>', $image->order)
          ->where([
            "entity" => $image->entity,
            "entity_id" => $image->entity_id,
          ])->get();

        foreach ($siblings as $sibling) {
          $sibling->update([
            "order" => $sibling->order-1
          ]);
        }

        $new_siblings = self::where('order', '>=', $order)
          ->where([
            "entity" => $entity,
            "entity_id" => $entity_id,
          ])->get();

        foreach ($new_siblings as $new_sibling){
          $new_sibling->update([
            "order" => $new_sibling->order+1
          ]);
        }

        $image->update([
          "entity" => $entity,
          "entity_id" => $entity_id,
          "order" => $order
        ]);

        return true;
      }

      $count = self::where('order', "<=", $order)
        ->where([
          "entity" => $entity,
          "entity_id" => $entity_id,
        ])
        ->count();

      $order = $order === $count ? $order : 1;

      if ($order < $image->order) {
        $imgs = self::whereBetween('order',[$order, $image->order])
          ->where([
            "entity" => $entity,
            "entity_id" => $entity_id,
          ])
          ->get();

        foreach ($imgs as $img) {
          $img_dn = self::find($img->id);
          $img_dn->update([
            "order" => $img->order+1
          ]);
        }

      } else {
        $imgs = self::whereBetween('order',[$image->order,$order])
          ->where([
            "entity" => $entity,
            "entity_id" => $entity_id,
          ])
          ->get();

        foreach ($imgs as $img) {
          $img_up = self::find($img->id);
          $img_up->update([
            "order" => $img->order-1
          ]);
        }
      }

      $image->update([
        "entity" => $entity,
        "entity_id" => $entity_id,
        "order" => $order
      ]);


      $images_null = self::where([
        "entity" => $entity,
        "entity_id" => $entity_id,
        "order" => null,
      ])
        ->get();

      foreach ($images_null as $key_null => $im_null){
        $max_order_ = self::where([
          'entity' => $entity,
          'entity_id' => $entity_id,
        ])
          ->max('order');

        $im_null->update([
          "order" => $max_order_+1
        ]);
      }
      return true;
    }
    return false;
  }

  /**
   * save images to server
   * @param $id
   */
  public static function delete_image($id): void
  {
    if ($id) {
      $image = self::find($id);

      if ($image) {
        unlink(public_path($image->url_original));
        $image->delete();
      }
    }
  }
}
