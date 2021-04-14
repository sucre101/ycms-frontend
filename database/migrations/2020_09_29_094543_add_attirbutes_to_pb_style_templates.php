<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttirbutesToPbStyleTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pb_style_templates', function (Blueprint $table) {
          $table->string('border')->nullable();
          $table->string('height')->nullable();
          $table->string('width')->nullable();
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
          $table->dropColumn('border')->nullable();
          $table->dropColumn('height')->nullable();
          $table->dropColumn('width')->nullable();
        });
    }
}
