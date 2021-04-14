<?php

namespace App\Modules\SocialFeed;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
  protected $table = "m_sf_post_tag";
  protected $guarded = [];

  public function tag()
  {
    return $this->hasOne(Tag::class, 'id', 'tag_id');
  }
  public function post()
  {
    return $this->hasOne(Post::class, 'id', 'post_id');
  }
}
