<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToAppAuthSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_auth_settings', function (Blueprint $table) {
            $table->string("name")->default(true);
            $table->string("first_name")->default(true);
            $table->string("last_name")->default(true);
            $table->string("middle_name")->default(true);
            $table->string("prefix")->default(true);
            $table->string("suffix")->default(true);
            $table->string("birthday")->default(true);
            $table->string("gender")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_auth_settings', function (Blueprint $table) {
            //
        });
    }
}
