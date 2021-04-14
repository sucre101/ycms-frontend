<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebViewResources extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('webview_resources', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('user_module_id');
      $table->string('src')->nullable();
      $table->jsonb('extended_data')->nullable();
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
    Schema::dropIfExists('webview_resources');
  }
}
