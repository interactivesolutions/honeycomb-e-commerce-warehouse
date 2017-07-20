<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToHcWarehousesStockHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hc_warehouses_stock_history', function(Blueprint $table)
		{
			$table->foreign('good_id', 'fk_hc_warehouses_stock_history_hc_goods1')->references('id')->on('hc_goods')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('user_id', 'fk_hc_warehouses_stock_history_hc_users1')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('warehouse_id', 'fk_hc_warehouses_stock_history_hc_warehouses1')->references('id')->on('hc_warehouses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('action_id', 'fk_hc_warehouses_stock_history_hc_warehouses_stock_history_1')->references('id')->on('hc_warehouses_stock_history_actions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hc_warehouses_stock_history', function(Blueprint $table)
		{
			$table->dropForeign('fk_hc_warehouses_stock_history_hc_goods1');
			$table->dropForeign('fk_hc_warehouses_stock_history_hc_users1');
			$table->dropForeign('fk_hc_warehouses_stock_history_hc_warehouses1');
			$table->dropForeign('fk_hc_warehouses_stock_history_hc_warehouses_stock_history_1');
		});
	}

}
