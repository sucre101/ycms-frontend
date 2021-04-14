<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableShopStoresToStructure extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_stores_to_structure', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('store_id');
      $table->unsignedBigInteger('structure_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_stores_to_structure');
  }
}
