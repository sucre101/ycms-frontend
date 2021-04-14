<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

class RenameColumnsStyleTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('styles', function (Blueprint $table) {
      $table->renameColumn('app_id', 'page_id')->change();
      $table->dropColumn('name');
      $table->unsignedBigInteger('user_module_id')->nullable();
      $table->dropColumn('value');
      $table->dropColumn('prop');
      $table->dropColumn('group');
      $table->jsonb('data')->nullable();
      $table->dropNestedSet();
//      $table->dropForeign('styles_app_id_foreign');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('styles', function (Blueprint $table) {
      $table->renameColumn('page_id', 'app_id')->change();
      $table->dropColumn('user_module_id');
      $table->string('name');
      $table->string('value')->nullable();
      $table->nestedSet();
      $table->string('prop')->nullable();
      $table->string('group')->nullable();
      $table->dropColumn('data');
//      $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
    });
  }
}
