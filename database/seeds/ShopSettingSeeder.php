<?php

use Illuminate\Database\Seeder;

class ShopSettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $apps = \App\App::all();

    foreach ($apps as $app) {
      DB::table('shop_settings')->insert([
        'app_id' => $app->id,
        'created_at' =>  \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
      ]);
    }
  }
}
