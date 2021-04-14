<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppUserNotificationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_user_notifications', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('app_id');
      $table->unsignedBigInteger('app_user_anon_id');
      $table->unsignedBigInteger('module_id')->nullable();
      $table->integer('level_id');
      $table->integer('status_id');
      $table->jsonb('data');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('app_user_notifications');
  }
}
