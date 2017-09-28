<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreOrderFieldToHcWarehousesStockSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hc_warehouses_stock_summary', function (Blueprint $table) {
            $table->float('pre_ordered', 10, 0)->nullable();
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
            $table->dropColumn('pre_ordered');
        });
    }
}
