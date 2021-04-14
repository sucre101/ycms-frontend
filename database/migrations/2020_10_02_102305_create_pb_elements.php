<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePbElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pb_elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('block_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->text('content')->nullable();
            $table->timestamps();

            $table->foreign('block_id')->references('id')->on('pb_blocks')->onDelete('cascade');
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
        Schema::dropIfExists('pb_elements');
    }
}
