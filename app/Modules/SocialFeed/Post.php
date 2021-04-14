<?php

namespace App\Modules\SocialFeed;

use App\UserModule;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $table = "m_sf_posts";
  protected $guarded = [];

  public function user_module()
  {
    return $this->hasOne(UserModule::class, 'id', 'user_module_id');
  }

  public function blocks()
  {
    return $this->hasMany(Block::class, 'post_id', 'id')->orderBy('order');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class, 'post_id', 'id');
  }
  public function post_tags()
  {
    return $this->hasMany(PostTag::class, 'post_id', 'id');
  }

  public function tags()
  {
    return $this->hasManyThrough(
      Tag::class,
      PostTag::class,
      'post_id',
      'id',
      'id',
      'tag_id',
    );
  }
}
