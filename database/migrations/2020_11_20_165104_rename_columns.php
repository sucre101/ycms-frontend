<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumns extends Migration
{
  public function up()
  {
    Schema::table('pages', function (Blueprint $table) {
      $table->renameColumn('module_id', 'user_module_id');
    });

    Schema::table('shop_stores', function (Blueprint $table) {
      $table->renameColumn('app_id', 'user_module_id');
    });

    Schema::table('m_sf_modules', function (Blueprint $table) {
      $table->renameColumn('app_id', 'user_module_id');
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
      $table->renameColumn('user_module_id', 'module_id');
    });

    Schema::table('shop_stores', function (Blueprint $table) {
      $table->renameColumn('user_module_id', 'app_id');
    });

    Schema::table('m_sf_modules', function (Blueprint $table) {
      $table->renameColumn('user_module_id', 'app_id');
    });
  }
}
