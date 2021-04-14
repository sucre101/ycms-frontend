<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductsSpecs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_products_specs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spec_id');
            $table->unsignedBigInteger('product_id');
            $table->jsonb('data');
            $table->integer('order');
            $table->timestamps();
            $table->foreign('spec_id')->references('id')->on('shop_specs')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_products_specs');
    }
}
