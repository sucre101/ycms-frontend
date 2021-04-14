<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InserCurenciesData extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    $data = [
      ['id' => 1, 'name' => 'EUR', 'short_name' => 'EUR'],
      ['id' => 2, 'name' => 'USD', 'short_name' => 'USD'],
      ['id' => 3, 'name' => 'RUB', 'short_name' => 'RUB'],
    ];

    $table = Db::table('shop_currencies');

    foreach ($data as $value) {
      $table->insert($value);
    }

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_currencies', function (Blueprint $table) {
      //
    });
  }
}
