<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopSpecs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_specs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('unit_id');
            $table->bigInteger('order');
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('shop_spec_groups')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('shop_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_specs');
    }
}
