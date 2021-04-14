<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
  const IN_PROD = ['list', 'e-commerce'];

  public function pages()
  {
    return $this->hasMany(Page::class, 'module_desc_id');
  }
}
