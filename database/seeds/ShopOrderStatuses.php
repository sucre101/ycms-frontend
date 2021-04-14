<?php

use Illuminate\Database\Seeder;

class ShopOrderStatuses extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $names = [
      ['name' => "Pending"],
      ['name' => "Processed"],
      ['name' => "Complete"],
      ['name' => "Canceled"]
    ];

    DB::table('shop_order_statuses')->insert($names);
  }
}
