<?php

use Illuminate\Database\Seeder;

class AppsTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run () {
    DB::table('apps')
      ->insert(
      [
        'user_id' => 1,
        'name' => "Hello World",
        'folder' => 'ycms-mobile',
        'slug' => 'Hello-World-1'
      ]
    );
  }
}
