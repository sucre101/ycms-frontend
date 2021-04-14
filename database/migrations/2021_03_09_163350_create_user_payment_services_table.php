<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentServicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_payment_services', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('user_id')->nullable();
      $table->unsignedBigInteger('app_id')->nullable();
      $table->unsignedBigInteger('payment_service_id')->nullable();
      $table->string('label')->nullable();
      $table->jsonb('payment_data')->nullable();
      $table->boolean('active')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_payment_services');
  }
}
