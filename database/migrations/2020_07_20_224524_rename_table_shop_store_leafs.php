<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTableShopStoreLeafs extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_store_leafs', function (Blueprint $table) {
      $table->dropForeign(['store_id']);
      $table->dropColumn('store_id');
    });

    Schema::rename('shop_store_leafs', 'shop_stores');

    Schema::table('shop_stores', function (Blueprint $table) {
      $table->integer('order')->nullable();
      $table->foreign('app_id')->references('id')->on('apps');
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
      $table->dropForeign(['app_id']);
    });

    Schema::rename('shop_stores', 'shop_store_leafs');

    Schema::table('shop_store_leafs', function (Blueprint $table) {
      $table->dropColumn('order');
    });
  }
}
