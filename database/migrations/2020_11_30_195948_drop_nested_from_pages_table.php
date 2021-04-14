<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropNestedFromPagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('pages', function (Blueprint $table) {
      $table->dropNestedSet();
      $table->dropColumn('pageable_id');
      $table->dropColumn('pageable_type');
      $table->dropColumn('tabbar');
      $table->string('short_title')->nullable()->change();
      $table->string('image')->nullable()->change();
      $table->string('icon')->nullable()->change();
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
      $table->nestedSet();
      $table->unsignedBigInteger('pageable_id')->nullable();
      $table->string('pageable_type')->nullable();
      $table->boolean('tabbar')->nullable();
      $table->string('short_title')->nullable()->change();
    });
  }
}
