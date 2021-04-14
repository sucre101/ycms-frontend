<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run ()
  {
    DB::table('modules')->insert([
        [
          'title' => "Folder",
          'name' => 'folder',
          'description' => 'folder',
          'image' => '/img/list.svg',
          'icon' => 'list',
          'back_path' => '',
          'front_folder' => 'Webview'
        ],
        [
          'title' => "Page Builder",
          'name' => 'page-builder',
          'description' => 'page builder',
          'image' => '/img/page-builder.svg',
          'icon' => 'document',
          'back_path' => '',
          'front_folder' => 'Webview'
        ],
        [
          'title' => "Form Builder",
          'name' => 'form-builder',
          'description' => 'form builder',
          'image' => '/img/form-builder.svg',
          'icon' => 'business',
          'back_path' => '',
          'front_folder' => 'Webview'
        ],
        [
          'title' => "eCommerce",
          'name' => 'e-commerce',
          'description' => 'e-commerce',
          'image' => '/img/e-commerce.svg',
          'icon' => 'cart',
          'back_path' => '',
          'front_folder' => 'Ecommerce'
        ],
        [
          'title' => "News Feed",
          'name' => 'news-feed',
          'description' => 'news feed',
          'image' => '/img/social-wall.svg',
          'icon' => 'megaphone',
          'back_path' => '',
          'front_folder' => 'Webview'
        ],
        [
          'title' => 'WebView',
          'name' => 'webview',
          'description' => 'Webview interface',
          'image' => '/img/social-wall.svg',
          'icon' => 'cart',
          'back_path' => '',
          'front_folder' => 'Webview'
        ]
      ]
    );
  }
}
