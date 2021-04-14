<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * @property String name
 * @property String slug
 * @property string folder
 * @property int id
 */
class App extends Model implements HasMedia
{
  use HasMediaTrait;

  // Open table for record all columns
  protected $guarded = [];
  /**
   * @var mixed
   */
  public $path;

  public function getImageUrlAttribute()
  {
    return url($this->getFirstMediaUrl());
  }

  public function registerMediaCollections()
  {
    $this->addMediaCollection('icon')->useDisk('media-lib')->singleFile();
  }

  public function registerMediaConversions(Media $media = null)
  {
    $this->addMediaConversion('thumb')->width(125);
  }
  //                        / Spatie/MediaLibrary

  // TODO: delete with package?
  // use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

  // Getters
  public function getPathAttribute()
  {
    return base_path('../ycms-apps/' . $this->folder);
  }


  // Relations
  public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
  {
    return $this->hasOne('App\User', 'id', 'user_id');
  }

  public function pages(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(Page::class)->orderBy('order');
  }

  public function pb_pages(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(\App\Modules\PageBuilder\Page::class);
  }

  public function styles(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(Style::class);
  }

  public function meta(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(Meta::class);
  }

  public function settings(): \Illuminate\Database\Eloquent\Relations\HasOne
  {
    return $this->hasOne('App\Modules\Ecommerce\ShopSetting', 'app_id', 'id');
  }

  public function appSettings()
  {
    return $this->hasMany(AppSetting::class, 'app_id', 'id');
  }

  public function storeStructure(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany('App\Modules\Ecommerce\ShopStoreStructure', 'app_id', 'id');
  }

  public function stores(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany('App\Modules\Ecommerce\ShopStoreLeaf', 'app_id', 'id');
  }

  public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany('App\Modules\Ecommerce\ShopCategory');
  }

  public function scopeSlug($query, $slug)
  {
    return $query->where('slug', $slug)->first();
  }

  public function labels(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany('App\Modules\Ecommerce\ShopLabelList', 'app_id', 'id')
      ->orderBy('id', 'asc');
  }

//  public function pages(): \Illuminate\Database\Eloquent\Relations\HasMany
//  {
//    return $this->hasMany('App\Page', 'app_id', 'id');
//  }

  public function auth_settings(): \Illuminate\Database\Eloquent\Relations\HasOne
  {
    return $this->hasOne('App\AuthSetting', 'app_id', 'id');
  }

  public function feed_modules(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany('App\SocialFeed\Module', 'app_id', 'id');
  }

  public function suffixes(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
  {
    return $this->hasManyThrough(
      NameSuffix::class,
      AppsToSuffixes::class,
      'app_id',
      'id',
      'id',
      'suffix_id',
      );
  }

  public function prefixes(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
  {
    return $this->hasManyThrough(
      NamePrefix::class,
      AppsToPrefixes::class,
      'app_id',
      'id',
      'id',
      'prefix_id',
      );
  }

  public function genders(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
  {
    return $this->hasManyThrough(
      Gender::class,
      AppsToGenders::class,
      'app_id',
      'id',
      'id',
      'gender_id',
      );
  }


  public function path($subPath = '')
  {
    return !$subPath ? $this->path : $this->path .= "/${subPath}";
  }

  /*                                STYLES                                    */
  public function storeStyle($args): void
  {
    $selector = $this->styles()->firstOrCreate([
      'name' => $args['selector'],
      'group' => $args['group'],
    ]);

    $rule = $selector->children()->firstOrNew([
      'app_id' => $this->id,
      'name' => $args['ruleName'],
      'group' => $args['group'],
    ]);
    $rule->prop = $args['propName'];
    $rule->value = $args['propVal'];

    $selector->appendNode($rule);
  }

  public function getStyle($param, $default = '')
  {
    $result = $this->styles()->firstWhere('name', $param);
    return $result ? $result->value : $default;
  }

  public function generateStyleFor($group)
  {
    $styles = $this->styles()
      ->where(['group' => $group, 'parent_id' => null])
      ->get();

    $style = $styles->reduce(function($carry, $selector) {
      $propNodes = $selector->children;
      $props = $propNodes->reduce(function($carry, $prop) {
        return $carry . "\n  {$prop->prop}: {$prop->value};";
      });
      return $carry . "{$selector->name} {{$props}\n}\n\n";
    });

    $stylePath = $this->path . "/src/generated-styles/{$group}.scss";
    file_put_contents($stylePath, $style);
  }
  /*                                /STYLES                                   */

  /*                                META                                      */
  public function storeMeta($newMeta)
  {
    foreach($newMeta as $key => $val) {
      $meta = $this->meta()->firstOrNew(['name' => $key]);
      $meta->value = $val;
      $meta->save();
    }
  }

  public function getMeta($param, $default = '')
  {
    $result = $this->meta()->firstWhere('name', $param);
    return $result ? $result->value : $default;
  }
  /*                                /META                                     */

  /*                                SETTINGS                                  */
  public function storeSettings($newSettings, $regenerate = false)
  {
//    dd($newSettings);
    foreach($newSettings as $key => $val) {
      $setting = $this->settings()->firstOrNew(['name' => $key]);
      $setting->value = $val;
      $setting->save();
    }
    if ($regenerate) {
      $this->generateConfig();
    }
  }

  public function getSetting($setting, $default = '')
  {
//    $result = $this->settings()->firstWhere('name', $setting);
//    return $result ? $result->value : $default;
    return 'iOS';
  }

  public function generateConfig()
  {
    $settings = $this
      ->settings()
      ->pluck('value', 'name')
      ->toJson(JSON_PRETTY_PRINT);
    $configPath = $this->path('src/app-config.json');
    file_put_contents($configPath, $settings);
  }
  /*                                /SETTINGS                                 */

  public function exportPagesConfig()
  {
    return file_put_contents(
      $this->path('src/pages.json'),
      $this->pages()->whereNull('parent_id')->orderBy('order')->get()
        ->toJson(JSON_PRETTY_PRINT)
    );
  }

  public function exportRoutes()
  {
    $pages = $this->pages()->orderBy('order')->get();
    $newRoutes = $pages->reduce(function($carry, $page){
      $modName = ucfirst($page->name);
      return $carry .
        "  {\n" .
        "    // {$page->title} \n" .
        "    path: '{$page->name}',\n" .
        "    loadChildren: () =>\n" .
        "      import('../pages/{$page->name}/{$page->name}.module')\n" .
        "      .then(m => m.{$modName}PageModule)\n" .
        "  },\n";
    });

    return preg_replace_file(
      $this->path('src/app/app-routing.module.ts'),
      '/(generated\n).+\n(.+\/gener)/s',
      "$1$newRoutes$2"
    );
  }
}
