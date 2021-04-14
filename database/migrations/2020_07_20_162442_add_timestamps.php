<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shop_product_to_label_list_to_store_leaf', function (Blueprint $table) {
        $table->timestamps();
      });
      Schema::table('shop_products_to_stores', function (Blueprint $table) {
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
      Schema::table('shop_product_to_label_list_to_store_leaf', function (Blueprint $table) {
        $table->dropTimestamps();
      });
      Schema::table('shop_products_to_stores', function (Blueprint $table) {
        $table->dropTimestamps();
      });
    }
}
