<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsAuthSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('app_auth_settings', function (Blueprint $table) {
        $table->renameColumn('prefix', 'prefix_id');
        $table->renameColumn('suffix', 'suffix_id');
        $table->renameColumn('gender', 'gender_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('app_auth_settings', function (Blueprint $table) {
        $table->renameColumn('prefix_id', 'prefix');
        $table->renameColumn('suffix_id', 'suffix');
        $table->renameColumn('gender_id', 'gender');
      });
    }
}
