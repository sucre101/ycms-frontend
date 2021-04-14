<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MList extends Model
{
  public function page()
  {
    return $this->morphOne('App\Page', 'module');
  }

  // TODO: delete?
  public function elements()
  {
    return $this->hasMany(MListElement::class);
  }
}
