<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportBuilds extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('export_builds', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('app_id');
      $table->integer('status_id')->default(1);
      $table->integer('version')->default(1);
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
    Schema::dropIfExists('export_builds');
  }
}
