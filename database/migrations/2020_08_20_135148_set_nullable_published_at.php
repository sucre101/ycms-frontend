<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNullablePublishedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('m_sf_posts', function (Blueprint $table) {
        $table->dateTime('published_at')->nullable()->change();;
        $table->string('rating_type')->nullable()->change();;
        $table->string('comment_rating_type')->nullable()->change();;
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
