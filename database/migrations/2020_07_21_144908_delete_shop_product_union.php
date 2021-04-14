<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteShopProductUnion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('shop_products', function (Blueprint $table) {
        $table->dropForeign('shop_products_union_id_foreign');
        $table->dropColumn('union_id');
      });
      Schema::dropIfExists('shop_product_unions');
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
