<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePbPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::dropIfExists('pb_pages');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::create('pb_pages', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('app_id');
        $table->unsignedBigInteger('template_id')->nullable();
        $table->string('title')->nullable();
        $table->string('thumb')->nullable();
        $table->string('short_content')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();

        $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
        $table->foreign('template_id')->references('id')->on('pb_style_templates')->onDelete('cascade');
      });
    }
}
