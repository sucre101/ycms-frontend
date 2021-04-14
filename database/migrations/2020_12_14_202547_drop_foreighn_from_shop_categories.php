<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeighnFromShopCategories extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_categories', function (Blueprint $table) {
      $table->dropForeign('shop_categories_app_id_foreign');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_categories', function (Blueprint $table) {
      $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
    });
  }
}
