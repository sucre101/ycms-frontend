<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppAuthSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_auth_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('app_id');
            $table->boolean('email');
            $table->time('email_debounce');
            $table->boolean('phone');
            $table->time('phone_debounce');
            $table->boolean('facebook');
            $table->string('facebook_app_id')->nullable();
            $table->boolean('google_plus');
            $table->string('google_plus_app_id')->nullable();
            $table->string('google_plus_android_client_id')->nullable();
            $table->boolean('vkontakte');
            $table->string('vkontakte_app_id')->nullable();
            $table->boolean('twitter');
            $table->string('twitter_app_id')->nullable();
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
        Schema::dropIfExists('app_auth_settings');
    }
}
