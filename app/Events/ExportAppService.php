<?php

namespace App\Events;


use App\App;
use App\AppSetting;
use App\Modules\WebVIew\Models\WebviewResources;
use App\Page;
use DOMAttr;
use DOMDocument;

class ExportAppService
{

  public $app_id;

  public function __construct($app)
  {
    $this->app_id = $app;
  }

  public function start() : void
  {
    $off = [];

    $pages = Page::where(['app_id' => $this->app_id, 'active' => true])->with('userModules.module')->get();

    $app = App::where('id', $this->app_id)->first();

    $off['app_name'] = $app->name;

    $settings = AppSetting::where('app_id', $this->app_id)->get();

    foreach ($settings as $setting) {
      $off['settings'][$setting->name] = $setting->value;
    }

    foreach ($pages as $page) {

      if ($page->userModules->module->name === 'e-commerce') {
        echo true;
      }

      if ($page->userModules->module->name === 'webview') {

        $src = WebviewResources::where('user_module_id', $page->userModules->id)->first();

        $off['modules'][$page->userModules->module->name] = [
          'id' => $page->userModules->id,
          'src' => $src->src ?? null,
          'alias' => $page->userModules->alias ?? false
        ];

      }

    }

    $folder = 'example';

    $filename = dirname(base_path()).'/backend/public/img/'.$folder.'/config.json';

    if (!file_exists($filename) && count($off)) {
      file_put_contents($filename, \GuzzleHttp\json_encode($off));
      chmod($filename, 0777);
    }

    $file  = dirname(base_path()).'/backend/public'. $off['settings']['app_icon_src'];

    copy($file, dirname(base_path()).'/backend/app-builds/e4qw/app_icon.png');

    // Create strings xml for android

    $xml_file_name = dirname(base_path()).'/backend/app-builds/e4qw/strings.xml';

    if (!file_exists($xml_file_name)) {
      $dom = new DOMDocument();
      $dom->encoding = 'utf-8';
      $dom->xmlVersion = '1.0';
      $dom->formatOutput = true;

      $root = $dom->createElement('resources');
      $resource_node = $dom->createElement('string', $off['app_name']);
      $attr_movie_id = new DOMAttr('name', 'app_name');
      $resource_node->setAttributeNode($attr_movie_id);
      $root->appendChild($resource_node);

      $resource_node2 = $dom->createElement('string', $off['app_name']);
      $attr_movie_id2 = new DOMAttr('name', 'title_activity_kimera');
      $resource_node2->setAttributeNode($attr_movie_id2);
      $root->appendChild($resource_node2);

      $dom->appendChild($root);
      $dom->save($xml_file_name);
    }

  }

}
