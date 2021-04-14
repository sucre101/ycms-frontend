<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PbTemplateChangeAppIdToModuleId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pb_style_templates', function (Blueprint $table) {
        $table->renameColumn('app_id', 'user_module_id')->change();

        $table->dropForeign('pb_style_templates_app_id_foreign');

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
      Schema::table('pb_style_templates', function (Blueprint $table) {
        $table->renameColumn('user_module_id', 'app_id')->change();

        $table->dropForeign('user_module_id');

        $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
      });
    }
}
