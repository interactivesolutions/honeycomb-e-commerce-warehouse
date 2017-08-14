<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignFieldToHcWarehousesStockHistoryActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hc_warehouses_stock_history_actions', function (Blueprint $table) {
            $table->enum('sign', ['-1', '0', '1'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hc_warehouses_stock_history_actions', function (Blueprint $table) {
            $table->dropColumn('sign');
        });
    }
}
