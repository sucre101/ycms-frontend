<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pb_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('entity');
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('url_original');
            $table->string('url_medium');
            $table->string('url_small');
            $table->integer('order');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('pb_style_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pb_images');
    }
}
