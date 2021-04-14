<?php

namespace App\Modules\SocialFeed;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
  protected $table = "m_sf_blocks";
  protected $guarded = [];

  public function post()
  {
    return $this->hasOne(Post::class, 'id', 'post_id');
  }
}
