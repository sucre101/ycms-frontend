<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pb_blocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->integer('order');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('page_id')->references('id')->on('pb_pages')->onDelete('cascade');
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
        Schema::dropIfExists('pb_blocks');
    }
}
