<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run () {
    DB::table('pages')
      ->insert([
      [
        'name' => 'home',
        'title' => 'Home',
        'short_title' => 'Home',
        'image' => '',
        'active' => true,
        'created_at' => '2020-06-08 05:10:30',
        'updated_at' => '2020-06-08 05:10:38',
        'module_id' => 1,
//        'module_type' => 'App\MList',
//        'module_desc_id' => 1,
        'pageable_id' => 1,
        'pageable_type' => 'App\MList',
        'deleted_at' => null,
        'icon' => 'home',
        'tabbar' => true,
        'app_id' => 1,
        'order' => 0,
        '_lft' => 1,
        '_rgt' => 2,
        'parent_id' => null,
      ]
    ]);
  }
}
