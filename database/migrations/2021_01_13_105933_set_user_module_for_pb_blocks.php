<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetUserModuleForPbBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pb_blocks', function (Blueprint $table) {
        $table->renameColumn('page_id', 'user_module_id')->change();

        $table->dropForeign('pb_blocks_page_id_foreign');

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
      Schema::table('pb_blocks', function (Blueprint $table) {
        $table->renameColumn('user_module_id', 'page_id')->change();

        $table->dropForeign('pb_blocks_user_module_id_foreign');

        $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
      });
    }
}
