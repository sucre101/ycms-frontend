<?php

use Illuminate\Database\Seeder;

class ShopUnitSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('shop_units')
      ->insert(
        [
          'name' => "кг",
          'type' => "float",
        ],
        );
  }
}
