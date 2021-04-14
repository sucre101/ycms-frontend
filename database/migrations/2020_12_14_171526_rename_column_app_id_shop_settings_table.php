<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnAppIdShopSettingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_settings', function (Blueprint $table) {
      $table->renameColumn('app_id', 'user_module_id')->change();
    });

    Schema::table('shop_categories', function (Blueprint $table) {
      $table->renameColumn('app_id', 'user_module_id')->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_settings', function (Blueprint $table) {
      $table->renameColumn('user_module_id', 'app_id')->change();
    });

    Schema::table('shop_categories', function (Blueprint $table) {
      $table->renameColumn('user_module_id', 'app_id')->change();
    });
  }
}
