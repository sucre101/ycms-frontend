<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAppIdToPageTable extends Migration
{
    public function up()
    {
      Schema::table('pages', function (Blueprint $table) {
        if (!Schema::hasColumn('pages', 'app_id')) {
          $table->unsignedInteger('app_id');
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
          if (Schema::hasColumn('pages', 'app_id')) {
            $table->dropColumn('app_id');
          }
        });
    }
}
