<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up ()
  {
    Schema::create('pages', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('app_id');
      $table->unsignedInteger('module_id');
      $table->string('name');
      $table->string('title');
      $table->string('short_title');
      $table->string('image');
      $table->boolean('active')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down ()
  {
    Schema::dropIfExists('pages');
  }

}
