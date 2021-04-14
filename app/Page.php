<?php

namespace App;

use App\Modules\Ecommerce\MEcommerce;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
  use SoftDeletes;
//  use NodeTrait;

  protected $guarded = [];

  public function getPathAttribute()
  {
    return $this->app->path("src/pages/{$this->name}");
  }

  public function getModuleNameAttribute()
  {
    return $this->modDesc->name;
  }

  public function getDataAttribute()
  {
    if ($this->moduleName == 'list')
      return $this->children()->orderBy('order')->get()
        ->toJson(JSON_PRETTY_PRINT);
    if ($this->moduleName == 'e-commerce')
      return $this->module->shops->toJson(JSON_PRETTY_PRINT);
  }

  public function app()
  {
    return $this->belongsTo(App::class);
  }

  public function userModule()
  {
    return $this->hasOne('App\UserModule', 'id', 'user_module_id');
  }

  // module description
  public function modDesc()
  {
    return $this->belongsTo(Module::class, 'module_desc_id');
  }

  /*                                  METHODS                                 */

  public function attachModule($moduleName)
  {
    switch ($moduleName) {
      case 'list':
        $this->module_type = MList::class;
        $module = new MList;
        $module->subheader = true;
        $module->text = true;
        $module->save();
        if ($this->name != 'home') {
          $this->title = "List {$module->id}";
          $this->short_title = "List {$module->id}";
        }
        break;
      case 'e-commerce':
        $this->module_type = MEcommerce::class;
        $module = new MEcommerce;
        $module->save();
        if ($this->name != 'home') {
          $this->title = "eCommerce";
          $this->short_title = "eCommerce";
        }
        break;
    }
    $this->module_id = $module->id;

    $this->save();
  }

  public function userModules()
  {
    return $this->hasOne(UserModule::class, 'id', 'user_module_id');
  }

  public function exportToIonic()
  {
    $tempDir = base_path('ionic-related/new_page');
    copy_dir(base_path('ionic-related/page'), $tempDir, '/^page/', $this->name);

    $files = collect(scandir($tempDir))
    ->filter(function($file){ return !\Str::startsWith($file, '.'); })
    ->map(function($file) use ($tempDir) {
      return "{$tempDir}/{$file}";
    });

    $replacements = collect([
      '/%pageClass%/' => ucfirst($this->name),
      '/%pageName%/' => $this->name,
      '/%modClass%/' => ucfirst(str_replace('-', '', $this->modDesc->name)),
      '/%modName%/' => $this->modDesc->name,
      '/%pageTitle%/' => $this->title,
    ]);
    if ($this->modDesc->name == 'list' && $this->root_pages == true)
      $replacements["/(from ').*data(.json)/"] = "$1../../pages$2";
    else
      file_put_contents($tempDir . '/data.json', $this->data);

    $files->each(function($file) use ($replacements) {
      $newFile = file_get_contents($file);
      $replacements->each(function($repl, $pattern) use (&$newFile) {
        $newFile = preg_replace($pattern, $repl, $newFile);
      });
      file_put_contents($file, $newFile);
    });

    copy_dir($tempDir, $this->app->path("src/pages/{$this->name}"));
    deleteDirectory($tempDir);
  }

  public function updateInIonic()
  {
    $files = collect(scandir($this->path))->reduce(function($carry, $file) {
      return !\Str::startsWith($file, '.')
        ? $carry->push("{$this->path}/{$file}") : $carry;
    }, collect());

    $replacements = collect([
      '/(ion-title>\n\s+).+?(\s+<\/ion-ti)/s' => "$1{$this->title}$2",
    ]);
    if ($this->modDesc->name == 'list' && $this->root_pages == true)
      $replacements["/(data from ').*(.json)/"] = "$1../../pages$2";
    else // FIXME: works only first statement?
      $replacements["/(data from ').*(.json)/"] = "$1./data$2";
      file_put_contents($this->path . '/data.json', $this->data);

    $files->each(function($file) use ($replacements) {
      $newFile = file_get_contents($file);
      $replacements->each(function($repl, $pattern) use (&$newFile) {
        $newFile = preg_replace($pattern, $repl, $newFile);
      });
      file_put_contents($file, $newFile);
    });
  }

  public function removeFromIonic()
  {
    return deleteDirectory($this->path);
  }
}
