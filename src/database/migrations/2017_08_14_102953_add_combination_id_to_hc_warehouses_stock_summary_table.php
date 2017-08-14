<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCombinationIdToHcWarehousesStockSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hc_warehouses_stock_summary', function (Blueprint $table) {
            $table->string('combination_id', 36)->nullable();

            $table->foreign('combination_id')->references('id')->on('hc_goods_combinations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_warehouses_stock_summary', function (Blueprint $table) {
            $table->dropForeign(['combination_id']);

            $table->dropColumn('combination_id');
        });
    }
}
