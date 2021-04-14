<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnStoreIdNullableTableShopStoreLeafs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_store_leafs', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable()->change();
            $table->unsignedBigInteger('app_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_store_leafs', function (Blueprint $table) {
            //
        });
    }
}
