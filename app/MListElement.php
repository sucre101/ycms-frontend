<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// TODO: delete?
class MListElement extends Model
{
  public function list()
  {
    return $this->belongsTo(MList::class);
  }
}
