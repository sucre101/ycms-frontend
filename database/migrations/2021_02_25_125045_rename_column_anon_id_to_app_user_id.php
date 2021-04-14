<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnAnonIdToAppUserId extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('app_users', function (Blueprint $table) {
      $table->renameColumn('app_id', 'app_user_anon_id')->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('app_users', function (Blueprint $table) {
      $table->renameColumn('app_user_anon_id', 'app_id')->change();
    });
  }
}
