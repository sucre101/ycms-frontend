<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopFilters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spec_id');
            $table->enum('display_type',['button', 'list']);
            $table->timestamps();
            $table->foreign('spec_id')->references('id')->on('shop_specs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_filters');
    }
}
