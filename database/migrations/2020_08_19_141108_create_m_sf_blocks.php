<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSfBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sf_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('post_id');
            $table->enum('type', ['html', 'image', 'video']);
            $table->text('content');
            $table->integer('order');
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('m_sf_posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sf_blocks');
    }
}
