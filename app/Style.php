<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Kalnoy\Nestedset\NodeTrait;

class Style extends Model
{
  use NodeTrait;

  protected $fillable = ['app_id', 'name', 'value', 'group'];

  public function app()
  {
    return $this->belongsTo(App::class);
  }
}
