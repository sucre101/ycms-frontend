<?php

namespace App\Modules\SocialFeed;

use App\UserModule;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
  protected $table = "m_sf_tags";
  protected $guarded = [];

  public function user_module()
  {
    return $this->hasOne(UserModule::class, 'id', 'user_module_id');
  }

  public function posts()
  {
    return $this->hasManyThrough(
      Post::class,
      PostTag::class,
      'tag_id',
      'id',
      'id',
      'post_id',
      );
  }
}


