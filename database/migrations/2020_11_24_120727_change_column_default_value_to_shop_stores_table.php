<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnDefaultValueToShopStoresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_stores', function (Blueprint $table) {
      $table->string('name', 255)->nullable(false)->change();
      $table->string('email', 150)->nullable()->change();
      $table->string('phone', 15)->nullable()->change();
      $table->string('address', 255)->nullable()->change();
      $table->float('lat')->nullable()->change();
      $table->float('lon')->nullable()->change();
      $table->string('zone_id', 150)->nullable()->change();
      $table->integer('currency_id')->nullable()->change();
      $table->float('tax_rate')->nullable()->change();
      $table->string('tax_name', 100)->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_stores', function (Blueprint $table) {
      //
    });
  }
}
