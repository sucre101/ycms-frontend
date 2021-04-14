<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSfPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sf_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_id');
            $table->string('title');
            $table->boolean('rating_state');
            $table->string('rating_type');
            $table->boolean('comment_state');
            $table->string('comment_rating_type');
            $table->dateTime('published_at');
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('m_sf_modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sf_posts');
    }
}
