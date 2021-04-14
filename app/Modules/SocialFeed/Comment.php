<?php

namespace App\Modules\SocialFeed;

use App\AppUser;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $table = "m_sf_comments";
  protected $guarded = [];

  public function post()
  {
    return $this->hasOne(Block::class, 'id', 'post_id');
  }

  public function user()
  {
    return $this->hasOne(AppUser::class, 'id', 'app_user_id');
  }

  public function parent()
  {
    return $this->hasOne(self::class, 'id', 'parent_id');
  }

  public function children()
  {
    return $this->hasMany(self::class, 'parent_id', 'id');
  }
}
