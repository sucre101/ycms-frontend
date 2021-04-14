<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNullTemplateProps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pb_style_templates', function (Blueprint $table) {
        $table->text('name')->nullable()->change();
        $table->text('margin')->nullable()->change();
        $table->text('padding')->nullable()->change();
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
        $table->text('content')->change();
      });
    }
}
