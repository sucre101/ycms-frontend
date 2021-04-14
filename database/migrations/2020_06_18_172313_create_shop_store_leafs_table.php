<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopStoreLeafsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_store_leafs', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('store_id');
      $table->string('name', 255)->nullable(false);
      $table->string('email', 150);
      $table->string('phone', 15);
      $table->string('address', 255);
      $table->float('lat');
      $table->float('lon');
      $table->string('zone_id', 150)->nullable();
      $table->integer('currency_id')->nullable();
      $table->float('tax_rate');
      $table->string('tax_name', 100);
      $table->boolean('default')->default(false);
      $table->foreign('store_id')->references('id')->on('shop_store_structures')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_store_leafs');
  }
}
