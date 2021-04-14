<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppImagesToEntity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_images_to_entity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('entity');
            $table->bigInteger('entity_id');
            $table->string('url_original');
            $table->string('url_medium');
            $table->string('url_small');
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('app_images_to_entity');
    }
}
