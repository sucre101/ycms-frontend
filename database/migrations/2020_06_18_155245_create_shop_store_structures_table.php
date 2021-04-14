<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopStoreStructuresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_store_structures', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('app_id');
      $table->string('name', 255);
      $table->string('img', 255)->nullable();
      $table->nestedSet();
      $table->timestamps();
      $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_store_structures');
  }
}
