<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTemplateForeigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pb_elements', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('restrict');
      });

        Schema::table('pb_pages', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('restrict');
      });

        Schema::table('pb_blocks', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('restrict');
      });

        Schema::table('pb_images', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('restrict');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('pb_elements', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('cascade');
      });

      Schema::table('pb_pages', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('cascade');
      });

      Schema::table('pb_blocks', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('cascade');
      });

      Schema::table('pb_images', function (Blueprint $table) {
        $table->dropForeign(['template_id']);
        $table
          ->foreign('template_id')
          ->references('id')
          ->on('pb_style_templates')
          ->onDelete('cascade');
      });
    }
}
