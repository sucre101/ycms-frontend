<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MSfPostsChangeModuleId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('m_sf_posts', function (Blueprint $table) {
        $table->renameColumn('module_id', 'user_module_id')->change();

        $table->dropForeign('m_sf_posts_module_id_foreign');

        $table->foreign('user_module_id')->references('id')->on('user_module')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('m_sf_posts', function (Blueprint $table) {
        $table->renameColumn('user_module_id', 'module_id')->change();

        $table->dropForeign('m_sf_posts_user_module_id_foreign');

        $table->foreign('module_id')->references('id')->on('m_sf_modules')->onDelete('cascade');
      });
    }
}
