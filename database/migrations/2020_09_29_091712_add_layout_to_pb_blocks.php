<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLayoutToPbBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pb_blocks', function (Blueprint $table) {
          $table->string('layout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pb_blocks', function (Blueprint $table) {
          $table->dropColumn('layout');
        });
    }
}
