<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('anon_id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('store_id');
            $table->integer('step');
            $table->string('phone');
            $table->string('email');
            $table->string('name');
            $table->longText('comment')->nullable();
            $table->unsignedBigInteger('order_status_id');
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
        Schema::dropIfExists('shop_orders');
    }
}
