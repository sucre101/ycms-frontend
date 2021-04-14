<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToAppUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->unsignedBigInteger('prefix_id')->nullable();
            $table->unsignedBigInteger('suffix_id')->nullable();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birthday')->nullable();

            $table->foreign('prefix_id')->references('id')->on('name_prefix')->onDelete('cascade');
            $table->foreign('suffix_id')->references('id')->on('name_suffix')->onDelete('cascade');
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_users', function (Blueprint $table) {
            $table->dropColumn('prefix_id');
            $table->dropColumn('suffix_id');
            $table->dropColumn('gender_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('birthday');
        });
    }
}
