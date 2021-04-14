<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableAppUserProfiles extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_user_profiles', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedBigInteger('app_user_id')->nullable(false);
      $table->string('fb_id')->nullable();
      $table->string('gp_id')->nullable();
      $table->string('tw_id')->nullable();
      $table->string('vk_id')->nullable();
      $table->unsignedInteger('prefix_id')->nullable();
      $table->unsignedInteger('suffix_id')->nullable();
      $table->unsignedInteger('gender_id')->nullable();
      $table->string('first_name', 255)->nullable();
      $table->string('last_name', 255)->nullable();
      $table->string('middle_name', 255)->nullable();
      $table->date('birthday')->nullable();
      $table->string('country_code', 10)->nullable();
      $table->unsignedInteger('status_id')->nullable();
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
    Schema::drop('app_user_profiles');
  }
}
