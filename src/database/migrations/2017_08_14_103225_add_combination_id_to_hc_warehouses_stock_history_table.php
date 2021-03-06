<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCombinationIdToHcWarehousesStockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hc_warehouses_stock_history', function (Blueprint $table) {
            $table->string('combination_id', 36)->nullable();

            $table->foreign('combination_id')->references('id')->on('hc_goods_combinations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        \DB::statement("ALTER TABLE `hc_warehouses_stock_history` CHANGE `user_id` `user_id` VARCHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_warehouses_stock_history', function (Blueprint $table) {
            $table->dropForeign(['combination_id']);

            $table->dropColumn('combination_id');
        });
    }
}
