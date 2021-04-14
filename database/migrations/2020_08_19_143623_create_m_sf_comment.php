<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMSfComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_sf_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('html')->nullable();
            $table->unsignedBigInteger('app_user_id')->nullable();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('m_sf_posts')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('m_sf_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sf_comment');
    }
}
