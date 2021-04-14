<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleToPbTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pb_style_templates', function (Blueprint $table) {
            $table->renameColumn('border','border_width');
            $table->string('border_type')->nullable();
            $table->string('border_color')->nullable();
            $table->string('border_radius')->nullable();
            $table->string('text_shadow')->nullable();
            $table->string('box_shadow')->nullable();
            $table->string('overflow')->nullable();
            $table->string('text_align')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pb_style_templates', function (Blueprint $table) {
          $table->renameColumn('border_width','border');
          $table->dropColumn('border_type');
          $table->dropColumn('border_color');
          $table->dropColumn('border_radius');
          $table->dropColumn('text_shadow');
          $table->dropColumn('box_shadow');
          $table->dropColumn('overflow');
          $table->dropColumn('text_align');
        });
    }
}
