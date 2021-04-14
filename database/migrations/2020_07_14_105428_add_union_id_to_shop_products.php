<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnionIdToShopProducts extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_products', function (Blueprint $table) {
      $table->unsignedBigInteger('union_id')->nullable();
      $table->foreign('union_id')->references('id')->on('shop_product_unions')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_products', function (Blueprint $table) {
      //
    });
  }
}
